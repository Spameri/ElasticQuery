<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class Stats
{

	public function __construct(
		private int $took,
		private bool $timedOut,
		private int $total,
	)
	{
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
