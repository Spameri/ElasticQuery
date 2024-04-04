<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping;


interface FilterInterface
	extends
	\Spameri\ElasticQuery\Entity\ArrayInterface,
	\Spameri\ElasticQuery\Collection\Item
{

	public function getType(): string;

}
