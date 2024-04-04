<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractNumberValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	public function __construct(
		private int $value,
	)
	{
	}


	public function value(): int
	{
		return $this->value;
	}

}
