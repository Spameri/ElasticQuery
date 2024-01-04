<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class Range implements LeafQueryInterface
{

	/**
	 * @param int|float|string|\DateTimeInterface|null $gte
	 * @param int|float|string|\DateTimeInterface|null $lte
	 */
	public function __construct(
		private string $field,
		private $gte = NULL,
		private $lte = NULL,
		private float $boost = 1.0,
	)
	{
		if ($gte === NULL && $lte === NULL) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Range must have at least one border value.',
			);
		}

		if ($lte && $gte && $lte < $gte) {
			if ($gte instanceof \DateTimeInterface) {
				$gteValue = $gte->format('U');

			} else {
				$gteValue = $gte;
			}

			if ($lte instanceof \DateTimeInterface) {
				$lteValue = $lte->format('U');

			} else {
				$lteValue = $lte;
			}

			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Input values does not make range. From: ' . $gteValue . ' To: ' . $lteValue,
			);
		}

	}


	public function key(): string
	{
		$gte = $this->gte instanceof \DateTimeInterface ? $this->gte->format('Y-m-d H:i:s') : $this->gte;
		$lte = $this->lte instanceof \DateTimeInterface ? $this->lte->format('Y-m-d H:i:s') : $this->lte;

		return 'range_' . $this->field . '_' . $gte . '_' . $lte;
	}


	public function toArray(): array
	{
		$array = [
			'range' => [
				$this->field => [
					'boost' => $this->boost,
				],
			],
		];

		if ($this->gte !== NULL) {
			$array['range'][$this->field]['gte'] =
				$this->gte instanceof \DateTimeInterface
					? $this->gte->format('Y-m-d H:i:s')
					: $this->gte;
		}

		if ($this->lte !== NULL) {
			$array['range'][$this->field]['lte'] =
				$this->lte instanceof \DateTimeInterface
					? $this->lte->format('Y-m-d H:i:s')
					: $this->lte;
		}

		return $array;
	}

}
