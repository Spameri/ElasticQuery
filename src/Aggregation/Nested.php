<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class Nested implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $path,
	)
	{
	}


	public function key(): string
	{
		return 'nested_' . $this->path;
	}


	public function toArray(): array
	{
		return [
			'nested' => [
				'path' => $this->path,
			],
		];
	}

}
