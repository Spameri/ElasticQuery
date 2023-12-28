<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class Stats
{

	/**
	 * @var int
	 */
	private $took;
	/**
	 * @var bool
	 */
	private $timedOut;
	/**
	 * @var int
	 */
	private $total;


	public function __construct(
		int $took
		, bool $timedOut
		, int $total,
	)
	{
		$this->took = $took;
		$this->timedOut = $timedOut;
		$this->total = $total;
	}


	public function took(): int
	{
		return $this->took;
	}


	public function timedOut(): bool
	{
		return $this->timedOut;
	}


	public function total(): int
	{
		return $this->total;
	}

}
