<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class Shards
{

	public function __construct(
		private int $total,
		private int $successful,
		private int $skipped,
		private int $failed,
	)
	{
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
