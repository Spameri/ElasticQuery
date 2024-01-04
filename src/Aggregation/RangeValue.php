<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class RangeValue implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	/**
	 * @param int|float|string|\DateTimeInterface|null $from
	 * @param int|float|string|\DateTimeInterface|null $to
	 */
	public function __construct(
		private string $key,
		private $from,
		private $to,
		private bool $fromEqual = TRUE,
		private bool $toEqual = TRUE,
	)
	{
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
