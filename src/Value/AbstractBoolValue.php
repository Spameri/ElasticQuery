<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractBoolValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	/**
	 * @var bool
	 */
	private $value;


	public function __construct(
		bool $value,
	)
	{
		$this->value = $value;
	}


	public function value(): bool
	{
		return $this->value;
	}

}
