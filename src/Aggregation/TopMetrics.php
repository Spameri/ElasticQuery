<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-metrics.html
 */
class TopMetrics implements LeafAggregationInterface
{

	/**
	 * @param array<int, string> $metrics Field names to capture.
	 * @param array<int, array<string, mixed>>|null $sort
	 */
	public function __construct(
		private array $metrics,
		private array|null $sort = null,
		private int|null $size = null,
		private string $key = 'top_metrics',
	)
	{
		if ($metrics === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'TopMetrics requires at least one metric field.',
			);
		}
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [];

		foreach ($this->metrics as $metric) {
			$array['metrics'][] = ['field' => $metric];
		}

		if ($this->sort !== null) {
			$array['sort'] = $this->sort;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		return ['top_metrics' => $array];
	}

}
