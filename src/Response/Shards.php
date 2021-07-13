<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class Shards
{

	/**
	 * @var int
	 */
	private $total;
	/**
	 * @var int
	 */
	private $successful;
	/**
	 * @var int
	 */
	private $skipped;
	/**
	 * @var int
	 */
	private $failed;


	public function __construct(
		int $total
		, int $successful
		, int $skipped
		, int $failed
	)
	{
		$this->total = $total;
		$this->successful = $successful;
		$this->skipped = $skipped;
		$this->failed = $failed;
	}


	public function total(): int
	{
		return $this->total;
	}


	public function successful(): int
	{
		return $this->successful;
	}


	public function skipped(): int
	{
		return $this->skipped;
	}


	public function failed(): int
	{
		return $this->failed;
	}

}
