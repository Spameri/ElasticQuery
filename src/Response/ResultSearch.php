<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/getting-started-search-API.html
 */
class ResultSearch implements ResultInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Response\Stats $stats,
		private \Spameri\ElasticQuery\Response\Shards $shards,
		private \Spameri\ElasticQuery\Response\Result\HitCollection $hitCollection,
		private \Spameri\ElasticQuery\Response\Result\AggregationCollection $aggregationCollection,
	)
	{
	}


	public function stats(): \Spameri\ElasticQuery\Response\Stats
	{
		return $this->stats;
	}


	public function shards(): \Spameri\ElasticQuery\Response\Shards
	{
		return $this->shards;
	}


	public function hits(): \Spameri\ElasticQuery\Response\Result\HitCollection
	{
		return $this->hitCollection;
	}


	public function aggregations(): \Spameri\ElasticQuery\Response\Result\AggregationCollection
	{
		return $this->aggregationCollection;
	}


	public function getHit(
		string $id,
	): \Spameri\ElasticQuery\Response\Result\Hit
	{
		/** @var \Spameri\ElasticQuery\Response\Result\Hit $hit */
		foreach ($this->hitCollection as $hit) {
			if ($hit->id() === $id) {
				return $hit;
			}
		}

		throw new \Spameri\ElasticQuery\Exception\HitNotFound(
			'Hit with id: ' . $id . 'not found.',
		);
	}


	public function getAggregation(
		string $name,
	): \Spameri\ElasticQuery\Response\Result\Aggregation
	{
		/** @var \Spameri\ElasticQuery\Response\Result\Aggregation $aggregation */
		foreach ($this->aggregationCollection as $aggregation) {
			if ($aggregation->name() === $name) {
				return $aggregation;
			}
		}

		throw new \Spameri\ElasticQuery\Exception\AggregationNotFound(
			'Aggregation with name: ' . $name . ' has not been found.',
		);
	}

}
