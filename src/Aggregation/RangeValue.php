<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class RangeValue implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public function __construct(
		private string $key,
		private int|float|string|\DateTimeInterface|null $from,
		private int|float|string|\DateTimeInterface|null $to,
		private bool $fromEqual = true,
		private bool $toEqual = true,
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
			'key' => $this->key,
			'from' => $from,
			'to' => $to,
		];
	}

}
