<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class TopHits implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private int $size,
	)
	{
	}


	public function key(): string
	{
		return 'top_hits_' . $this->size;
	}


	public function toArray(): array
	{
		return [
			'top_hits' => [
				'size' => $this->size,
			],
		];
	}

}
