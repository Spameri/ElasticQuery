<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class RangeValue implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	private string $key;

	/**
	 * @var int|float|string|\DateTimeInterface|null
	 */
	private $from;

	/**
	 * @var int|float|string|\DateTimeInterface|null
	 */
	private $to;

	private bool $fromEqual;

	private bool $toEqual;

	/**
	 * @param int|float|string|\DateTimeInterface|null $from
	 * @param int|float|string|\DateTimeInterface|null $to
	 */
	public function __construct(
		string $key,
		$from,
		$to,
		bool $fromEqual = TRUE,
		bool $toEqual = TRUE,
	)
	{
		$this->key = $key;
		$this->from = $from;
		$this->to = $to;
		$this->fromEqual = $fromEqual;
		$this->toEqual = $toEqual;
	}


	public function key(): string
	{
		return $this->key;
	}


	public function toArray(): array
	{
		$from = $this->from;
		$to = $this->to;

		if ( ! $this->fromEqual && \is_int($from)) {
			$from++;
		}
		if ($this->toEqual && \is_int($to)) {
			$to++;
		}

		return [
			'key'  => $this->key,
			'from' => $from,
			'to' => $to,
		];
	}

}
