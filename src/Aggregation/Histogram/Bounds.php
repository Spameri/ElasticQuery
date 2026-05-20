<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Histogram;


/**
 * Numeric bounds for histogram extended_bounds / hard_bounds.
 */
class Bounds implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private float|int $min,
		private float|int $max,
	)
	{
	}


	/**
	 * @return array<string, float|int>
	 */
	public function toArray(): array
	{
		return [
			'min' => $this->min,
			'max' => $this->max,
		];
	}

}
