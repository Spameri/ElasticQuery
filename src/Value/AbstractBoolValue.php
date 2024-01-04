<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractBoolValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	public function __construct(
		private bool $value,
	)
	{
	}


	public function value(): bool
	{
		return $this->value;
	}

}
