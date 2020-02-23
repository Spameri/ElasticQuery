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


	public function __construct(
		$key
		, int $docCount
		, ?int $position = NULL
		, $from = NULL
		, $to = NULL
	)
	{
		$this->key = $key;
		$this->docCount = $docCount;
		$this->position = $position;
		$this->from = $from;
		$this->to = $to;
	}


	public function key() : string
	{
		return $this->key;
	}


	public function docCount() : int
	{
		return $this->docCount;
	}


	public function position() : ?int
	{
		return $this->position;
	}


	public function from()
	{
		return $this->from;
	}


	public function to()
	{
		return $this->to;
	}

}
