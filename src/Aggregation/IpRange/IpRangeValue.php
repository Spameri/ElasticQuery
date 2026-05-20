<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\IpRange;


/**
 * Single ip_range bucket — either from/to bounds or a CIDR mask.
 */
class IpRangeValue implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public function __construct(
		private string $key,
		private string|null $from = null,
		private string|null $to = null,
		private string|null $mask = null,
	)
	{
		if ($mask === null && $from === null && $to === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'IpRangeValue requires mask or at least one of from/to.',
			);
		}
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, string>
	 */
	public function toArray(): array
	{
		$array = ['key' => $this->key];

		if ($this->mask !== null) {
			$array['mask'] = $this->mask;

		} else {
			if ($this->from !== null) {
				$array['from'] = $this->from;
			}
			if ($this->to !== null) {
				$array['to'] = $this->to;
			}
		}

		return $array;
	}

}
