<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result\Aggregation;


class Bucket
{

	public function __construct(
		private string $key,
		private int $docCount,
		private int|null $position = null,
		private int|float|null $from = null,
		private int|float|null $to = null,
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	public function docCount(): int
	{
		return $this->docCount;
	}


	public function position(): int|null
	{
		return $this->position;
	}


	public function from(): float|int|null
	{
		return $this->from;
	}


	public function to(): float|int|null
	{
		return $this->to;
	}

}
