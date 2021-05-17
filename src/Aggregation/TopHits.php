<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class TopHits implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	private int $size;


	public function __construct(
		int $size
	) {
		$this->size = $size;
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
