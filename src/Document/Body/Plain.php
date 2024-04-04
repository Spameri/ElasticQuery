<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document\Body;


class Plain implements \Spameri\ElasticQuery\Document\BodyInterface
{

	public function __construct(
		private array $parameters,
	)
	{
	}


	public function toArray(): array
	{
		return $this->parameters;
	}

}
