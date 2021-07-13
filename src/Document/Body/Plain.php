<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document\Body;


class Plain implements \Spameri\ElasticQuery\Document\BodyInterface
{

	/**
	 * @var array
	 */
	private $parameters;


	public function __construct(
		array $parameters
	)
	{
		$this->parameters = $parameters;
	}


	public function toArray(): array
	{
		return $this->parameters;
	}

}
