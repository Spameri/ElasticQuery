<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultMapper
{

	public function map(
		array $elasticSearchResponse
	) : ResultInterface
	{
		if (isset($elasticSearchResponse['found'])) {
			$result = $this->mapSingleResult($elasticSearchResponse);

		} elseif (isset($elasticSearchResponse['hits'])) {
			$result = $this->mapSearchResults($elasticSearchResponse);

		} elseif (isset($elasticSearchResponse['items'])) {
			$result = $this->mapBulkResult($elasticSearchResponse);

		} else {
			throw new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped($elasticSearchResponse);
		}

		return $result;
	}


	public function mapSingleResult(
		array $elasticSearchResponse
	) : ResultSingle
	{
		return new ResultSingle(
			$this->mapHit($elasticSearchResponse, 0),
			$this->mapSingleStats($elasticSearchResponse)
		);
	}


	public function mapBulkResult(
		array $elasticSearchResponse
	) : ResultBulk
	{
		return new ResultBulk(
			$this->mapStats($elasticSearchResponse),
			$this->mapBulkActions($elasticSearchResponse['items'])
		);
	}


	public function mapSearchResults(
		array $elasticSearchResponse
	) : ResultSearch
	{
		return new ResultSearch(
			$this->mapStats($elasticSearchResponse),
			$this->mapShards($elasticSearchResponse),
			$this->mapHits($elasticSearchResponse),
			$this->mapAggregations($elasticSearchResponse)
		);
	}


	public function mapHits(
		array $elasticSearchResponse
	) : \Spameri\ElasticQuery\Response\Result\HitCollection
	{
		$hits = [];
		foreach ($elasticSearchResponse['hits']['hits'] as $hitPosition => $hit) {
			$hits[] = $this->mapHit($hit, (int) $hitPosition);
		}

		return new \Spameri\ElasticQuery\Response\Result\HitCollection(
			... $hits
		);
	}


	private function mapHit(
		array $hit
		, int $position
	) : \Spameri\ElasticQuery\Response\Result\Hit
	{
		return new \Spameri\ElasticQuery\Response\Result\Hit(
			$hit['_source'] ?? [],
			$position,
			$hit['_index'],
			$hit['_type'],
			$hit['_id'],
			$hit['_score'] ?? 1,
			$hit['version'] ?? 0
		);
	}


	public function mapBulkActions(
		array $elasticSearchResponse
	) : \Spameri\ElasticQuery\Response\Result\BulkActionCollection
	{
		$bulkActions = [];
		foreach ($elasticSearchResponse as $actionType => $action) {
			$bulkActions[] = $this->mapBulkAction($action, $actionType);
		}

		return new \Spameri\ElasticQuery\Response\Result\BulkActionCollection(
			... $bulkActions
		);
	}


	public function mapBulkAction(
		array $bulkAction,
		string $actionType
	) : \Spameri\ElasticQuery\Response\Result\BulkAction
	{
		return new \Spameri\ElasticQuery\Response\Result\BulkAction(
			$actionType,
			$bulkAction['_index'],
			$bulkAction['_type'],
			$bulkAction['_id'],
			$bulkAction['_version'],
			$bulkAction['result'],
			$this->mapShards($bulkAction),
			$bulkAction['status'],
			$bulkAction['_seq_no'],
			$bulkAction['_primary_term']
		);
	}


	public function mapAggregations(
		array $elasticSearchResponse
	) : \Spameri\ElasticQuery\Response\Result\AggregationCollection
	{
		$aggregationArray = [];
		$i = 0;
		foreach ($elasticSearchResponse['aggregations'] as $aggregationName => $aggregation) {
			$aggregationArray[] = $this->mapAggregation($aggregationName, $i, $aggregation);
			$i++;
		}

		return new \Spameri\ElasticQuery\Response\Result\AggregationCollection(
			... $aggregationArray
		);
	}


	private function mapAggregation(
		string $name
		, int $position
		, array $aggregationArray
	) : \Spameri\ElasticQuery\Response\Result\Aggregation
	{
		$buckets = [];
		$aggregations = [];
		foreach ($aggregationArray['buckets'] as $bucketPosition => $bucket) {
			$buckets[] = $this->mapBucket($bucketPosition, $bucket);
		}

		if (isset($aggregationArray['aggregations'])) {
			$i = 0;
			foreach ($aggregationArray['aggregations'] as $aggregationName => $aggregation) {
				$aggregations[] = $this->mapAggregation($aggregationName, $i, $aggregation);
				$i++;
			}
		}

		return new \Spameri\ElasticQuery\Response\Result\Aggregation(
			$name,
			$position,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(
				... $buckets
			),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(
				... $aggregations
			)
		);
	}


	private function mapBucket(
		int $bucketPosition
		, array $bucketArray
	) : \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket
	{
		return new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			$bucketArray['key'],
			$bucketArray['doc_count'],
			$bucketPosition
		);
	}


	public function mapStats(
		array $elasticSearchResponse
	) : Stats
	{
		return new Stats(
			$elasticSearchResponse['took'],
			$elasticSearchResponse['timed_out'],
			$elasticSearchResponse['hits']['total']
		);
	}


	public function mapSingleStats(
		array $elasticSearchResponse
	) : StatsSingle
	{
		return new StatsSingle(
			$elasticSearchResponse['version'] ?? 0,
			$elasticSearchResponse['found']
		);
	}


	public function mapShards(
		array $elasticSearchResponse
	) : Shards
	{
		return new Shards(
			$elasticSearchResponse['_shards']['total'],
			$elasticSearchResponse['_shards']['successful'],
			$elasticSearchResponse['_shards']['skipped'],
			$elasticSearchResponse['_shards']['failed']
		);
	}

}
