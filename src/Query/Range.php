<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class Range implements LeafQueryInterface
{

	/**
	 * @var string
	 */
	private $field;
	/**
	 * @var int|float|\DateTimeInterface|null
	 */
	private $gte;
	/**
	 * @var int|float|\DateTimeInterface|null
	 */
	private $lte;
	/**
	 * @var float
	 */
	private $boost;


	public function __construct(
		string $field,
		$gte = NULL,
		$lte = NULL,
		float $boost = 1.0
	)
	{
		if ($gte === NULL && $lte === NULL) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Range must have at least one border value.'
			);
		}

		if ($lte && $gte && $lte < $gte) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Input values does not make range. From: ' . $gte . ' To: ' . $lte
			);
		}

		$this->field = $field;
		$this->gte = $gte;
		$this->lte = $lte;
		$this->boost = $boost;
	}


	public function key() : string
	{
		return $this->field . '_' . $this->gte . '_' . $this->lte;
	}


	public function toArray() : array
	{
		$array = [
			'range' => [
				$this->field => [
					'boost' => $this->boost,
				],
			],
		];

		if ($this->gte !== NULL) {
			$array['range'][$this->field]['gte'] = $this->gte instanceof \DateTimeInterface ? $this->gte->format('Y-m-d H:i:s') : $this->gte;
		}

		if ($this->lte !== NULL) {
			$array['range'][$this->field]['lte'] = $this->lte instanceof \DateTimeInterface ? $this->lte->format('Y-m-d H:i:s') : $this->gte;
		}

		return $array;
	}

}
