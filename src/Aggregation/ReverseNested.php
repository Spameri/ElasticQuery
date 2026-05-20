<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-reverse-nested-aggregation.html
 */
class ReverseNested implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string|null $path = null,
	)
	{
	}


	public function key(): string
	{
		return 'reverse_nested_' . ($this->path ?? 'root');
	}


	/**
	 * @return array<string, array<string, mixed>|\stdClass>
	 */
	public function toArray(): array
	{
		if ($this->path === null) {
			return [
				'reverse_nested' => new \stdClass(),
			];
		}

		return [
			'reverse_nested' => [
				'path' => $this->path,
			],
		];
	}

}
