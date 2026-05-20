<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\WeightedAvg;


/**
 * Value or weight side of a weighted_avg aggregation.
 *
 * One of $field or $script must be provided.
 */
class WeightedAvgValue implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private string|null $field = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private float|int|string|null $missing = null,
	)
	{
		if ($field === null && $script === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'WeightedAvgValue requires either field or script.',
			);
		}
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->field !== null) {
			$array['field'] = $this->field;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		return $array;
	}

}
