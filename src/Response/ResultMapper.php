<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultMapper
{

	public function map(
		array $elasticSearchResponse,
	): ResultInterface
	{
		if (isset($elasticSearchResponse['found'])) {
			$result = $this->mapSingleResult($elasticSearchResponse);

		} elseif (isset($elasticSearchResponse['hits'])) {
			$result = $this->mapSearchResults($elasticSearchResponse);

		} elseif (isset($elasticSearchResponse['items'])) {
			$result = $this->mapBulkResult($elasticSearchResponse);

		} elseif (isset($elasticSearchResponse['version']['number'])) {
			$result = $this->mapVersionResults($elasticSearchResponse);

		} else {
			throw new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped((string) \json_encode($elasticSearchResponse));
		}

		return $result;
	}


	public function mapSingleResult(
		array $elasticSearchResponse,
	): ResultSingle
	{
		return new ResultSingle(
			$this->mapHit($elasticSearchResponse, 0),
			$this->mapSingleStats($elasticSearchResponse),
		);
	}


	public function mapBulkResult(
		array $elasticSearchResponse,
	): ResultBulk
	{
		return new ResultBulk(
			$this->mapStats($elasticSearchResponse),
			$this->mapBulkActions($elasticSearchResponse['items']),
		);
	}


	public function mapVersionResults(
		array $elasticSearchResponse,
	): ResultVersion
	{
		return new ResultVersion(
			name: $elasticSearchResponse['name'],
			clusterName: $elasticSearchResponse['cluster_name'],
			clusterUUID: $elasticSearchResponse['cluster_uuid'],
			version: new \Spameri\ElasticQuery\Response\Result\Version(
				number: $elasticSearchResponse['version']['number'],
				buildHash: $elasticSearchResponse['version']['build_hash'],
				buildSnapshot: $elasticSearchResponse['version']['build_snapshot'],
				luceneVersion: $elasticSearchResponse['version']['lucene_version'],
				buildFlavor: $elasticSearchResponse['version']['build_flavor'] ?? null,
				buildType: $elasticSearchResponse['version']['build_type'] ?? null,
				buildDate: $elasticSearchResponse['version']['build_date'] ?? null,
				minimumWireCompatibility: $elasticSearchResponse['version']['minimum_wire_compatibility_version'] ?? null,
				minimumIndexCompatibility: $elasticSearchResponse['version']['minimum_index_compatibility_version'] ?? null,
			),
			tagLine: $elasticSearchResponse['tagline'],
		);
	}


	public function mapSearchResults(
		array $elasticSearchResponse,
	): ResultSearch
	{
		return new ResultSearch(
			$this->mapStats($elasticSearchResponse),
			$this->mapShards($elasticSearchResponse),
			$this->mapHits($elasticSearchResponse),
			$this->mapAggregations($elasticSearchResponse),
		);
	}


	public function mapHits(
		array $elasticSearchResponse,
	): \Spameri\ElasticQuery\Response\Result\HitCollection
	{
		$hits = [];
		foreach ($elasticSearchResponse['hits']['hits'] as $hitPosition => $hit) {
			$hits[] = $this->mapHit($hit, (int) $hitPosition);
		}

		return new \Spameri\ElasticQuery\Response\Result\HitCollection(
			... $hits,
		);
	}


	private function mapHit(
		array $hit,
		int $position,
	): \Spameri\ElasticQuery\Response\Result\Hit
	{
		return new \Spameri\ElasticQuery\Response\Result\Hit(
			$hit['_source'] ?? [],
			$position,
			$hit['_index'],
			$hit['_type'] ?? '_doc',
			$hit['_id'],
			$hit['_score'] ?? 1,
			$hit['version'] ?? 0,
		);
	}


	public function mapBulkActions(
		array $elasticSearchResponse,
	): \Spameri\ElasticQuery\Response\Result\BulkActionCollection
	{
		$bulkActions = [];
		foreach ($elasticSearchResponse as $actionType => $action) {
			$bulkActions[] = $this->mapBulkAction($action, $actionType);
		}

		return new \Spameri\ElasticQuery\Response\Result\BulkActionCollection(
			... $bulkActions,
		);
	}


	public function mapBulkAction(
		array $bulkAction,
		string $actionType,
	): \Spameri\ElasticQuery\Response\Result\BulkAction
	{
		return new \Spameri\ElasticQuery\Response\Result\BulkAction(
			$actionType,
			$bulkAction['_index'],
			$bulkAction['_type'] ?? '_doc',
			$bulkAction['_id'],
			$bulkAction['_version'],
			$bulkAction['result'],
			$this->mapShards($bulkAction),
			$bulkAction['status'],
			$bulkAction['_seq_no'],
			$bulkAction['_primary_term'],
		);
	}


	public function mapAggregations(
		array $elasticSearchResponse,
	): \Spameri\ElasticQuery\Response\Result\AggregationCollection
	{
		$aggregationArray = [];
		$i = 0;

		if (isset($elasticSearchResponse['aggregations'])) {
			foreach ($elasticSearchResponse['aggregations'] as $aggregationName => $aggregation) {
				$aggregationArray[] = $this->mapAggregation($aggregationName, $i, $aggregation);
				$i++;
			}
		}

		return new \Spameri\ElasticQuery\Response\Result\AggregationCollection(
			... $aggregationArray,
		);
	}


	private function mapAggregation(
		string $name,
		int $position,
		array $aggregationArray,
	): \Spameri\ElasticQuery\Response\Result\Aggregation
	{
		$i = 0;
		$buckets = [];
		$hits = [];
		$aggregations = [];

		if (isset($aggregationArray['buckets'])) {
			foreach ($aggregationArray['buckets'] as $bucketPosition => $bucket) {
				$buckets[] = $this->mapBucket($bucketPosition, $bucket);
			}
		}

		if (isset($aggregationArray[$name]['buckets'])) {
			foreach ($aggregationArray[$name]['buckets'] as $bucketPosition => $bucket) {
				$buckets[] = $this->mapBucket($bucketPosition, $bucket);
			}
		}

		if (isset($aggregationArray[$name]['hits'])) {
			foreach ($aggregationArray[$name]['hits'] as $hitPosition => $hit) {
				$hits[] = $this->mapHit($hit, $hitPosition);
			}
		}

		if (
			! isset($aggregationArray['buckets'])
			&& ! isset($aggregationArray[$name]['buckets'])
			&& isset($aggregationArray['value'])
		) {
			$buckets[] = $this->mapBucket(
				0, [
				'doc_count' => $aggregationArray['value'],
			],
			);
		}

		if (isset($aggregationArray['doc_count']) && $aggregationArray['doc_count'] > 0) {
			/**
			 * @var string $aggregationName
			 * @var array<mixed> $aggregation
			 */
			foreach ($aggregationArray as $aggregationName => $aggregation) {
				if ( ! \is_array($aggregation)) {
					continue;
				}
				$aggregations[] = $this->mapAggregation($aggregationName, $i, $aggregation);
				$i++;
			}
		}

		if (isset($aggregationArray['aggregations'])) {
			foreach ($aggregationArray['aggregations'] as $aggregationName => $aggregation) {
				$aggregations[] = $this->mapAggregation($aggregationName, $i, $aggregation);
				$i++;
			}
		}

		return new \Spameri\ElasticQuery\Response\Result\Aggregation(
			$name,
			$position,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(
				... $buckets,
			),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(
				... $aggregations,
			),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(
				... $hits,
			),
		);
	}


	private function mapBucket(
		int|null $bucketPosition,
		array $bucketArray,
	): \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket
	{
		return new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			$bucketArray['key'] ?? (string) $bucketPosition,
			$bucketArray['doc_count'],
			\is_int($bucketPosition) ? $bucketPosition : null,
			$bucketArray['from'] ?? null,
			$bucketArray['to'] ?? null,
		);
	}


	public function mapStats(
		array $elasticSearchResponse,
	): Stats
	{
		$total = 0;
		if (\is_int($elasticSearchResponse['hits']['total'])) {
			$total = $elasticSearchResponse['hits']['total'];

		} elseif (isset($elasticSearchResponse['hits']['total']['value'])) {
			$total = $elasticSearchResponse['hits']['total']['value'];
		}

		return new Stats(
			$elasticSearchResponse['took'],
			$elasticSearchResponse['timed_out'],
			$total,
		);
	}


	public function mapSingleStats(
		array $elasticSearchResponse,
	): StatsSingle
	{
		return new StatsSingle(
			$elasticSearchResponse['version'] ?? 0,
			$elasticSearchResponse['found'],
		);
	}


	public function mapShards(
		array $elasticSearchResponse,
	): Shards
	{
		return new Shards(
			$elasticSearchResponse['_shards']['total'],
			$elasticSearchResponse['_shards']['successful'],
			$elasticSearchResponse['_shards']['skipped'] ?? 0,
			$elasticSearchResponse['_shards']['failed'],
		);
	}

}
