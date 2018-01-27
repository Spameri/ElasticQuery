<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class NumberValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	/**
	 * @var int
	 */
	private $value;


	public function __construct(
		int $value
	)
	{
		$this->value = $value;
	}


	public function value() : int
	{
		return $this->value;
	}

}
