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
	 * @var int
	 */
	private $position;


	public function __construct(
		string $key
		, int $docCount
		, int $position
	)
	{
		$this->key = $key;
		$this->docCount = $docCount;
		$this->position = $position;
	}


	public function key() : string
	{
		return $this->key;
	}


	public function docCount() : int
	{
		return $this->docCount;
	}


	public function position() : int
	{
		return $this->position;
	}

}
