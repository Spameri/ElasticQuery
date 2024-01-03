<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class AbstractStringValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	/**
	 * @var string
	 */
	private $value;


	public function __construct(
		string $value,
	)
	{
		$this->value = $value;
	}


	public function value(): string
	{
		return $this->value;
	}

}
