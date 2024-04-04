<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractFloatValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	public function __construct(
		private float $value,
	)
	{
	}


	public function value(): float
	{
		return $this->value;
	}

}
