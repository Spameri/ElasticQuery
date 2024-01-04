<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result\Aggregation;


class Bucket
{

	private ?int $position = NULL;

	/**
	 * @phpstan-param int|float|null $from
	 * @phpstan-param int|float|null $to
	 */
	public function __construct(
		private string $key,
		private int $docCount,
		int|null $position = NULL,
		private $from = NULL,
		private $to = NULL,
	)
	{
		$this->position = $position;
	}


	public function key(): string
	{
		return (string) $this->key;
	}


	public function docCount(): int
	{
		return $this->docCount;
	}


	public function position(): int|null
	{
		return $this->position;
	}


	/**
	 * @return int|float|null
	 */
	public function from()
	{
		return $this->from;
	}


	/**
	 * @return int|float|null
	 */
	public function to()
	{
		return $this->to;
	}

}
