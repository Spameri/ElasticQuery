<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Value;


abstract class FloatValue implements \Spameri\ElasticQuery\Value\ValueInterface
{

	/**
	 * @var float
	 */
	private $value;


	public function __construct(
		float $value
	)
	{
		$this->value = $value;
	}


	public function value() : float
	{
		return $this->value;
	}

}
