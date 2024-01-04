<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractStringValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	public function __construct(
		private string $value,
	)
	{
	}


	public function value(): string
	{
		return $this->value;
	}

}
