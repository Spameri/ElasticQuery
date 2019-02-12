<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultMapper
{

	public function map(
		array $elasticSearchResponse
	) : Result
	{
		return new Result(
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
			$hit['_source'],
			$position,
			$hit['_index'],
			$hit['_type'],
			$hit['_id'],
			$hit['_score']
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
