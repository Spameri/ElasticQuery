<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class Alias implements \Spameri\ElasticQuery\Entity\ArrayInterface, \Spameri\ElasticQuery\Collection\Item
{

	public function __construct(
		private string $name,
	)
	{
	}


	public function name(): string
	{
		return $this->name;
	}


	public function key(): string
	{
		return $this->name;
	}


	public function toArray(): array
	{
		return [];
	}

}
