<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class StatsSingle
{

	public function __construct(
		private int $version,
		private bool $found,
	)
	{
	}


	public function version(): int
	{
		return $this->version;
	}


	public function found(): bool
	{
		return $this->found;
	}

}
