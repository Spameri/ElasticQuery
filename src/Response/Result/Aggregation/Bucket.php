<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result\Aggregation;


class Bucket
{

	/**
	 * @var string
	 */
	private $key;
	/**
	 * @var int
	 */
	private $docCount;
	/**
	 * @var int|null
	 */
	private $position;

	/**
	 * @var int|float|null
	 */
	private $from;

	/**
	 * @var int|float|null
	 */
	private $to;


	/**
	 * @phpstan-param int|float|null $from
	 * @phpstan-param int|float|null $to
	 */
	public function __construct(
		string $key
		, int $docCount
		, int|null $position = NULL
		, $from = NULL
		, $to = NULL,
	)
	{
		$this->key = $key;
		$this->docCount = $docCount;
		$this->position = $position;
		$this->from = $from;
		$this->to = $to;
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
