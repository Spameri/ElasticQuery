<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document;


class Bulk implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private array $data,
	)
	{
	}


	public function toArray(): array
	{
		return [
			'body' => $this->data,
		];
	}

}
