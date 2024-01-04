<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Terms;

class Order implements \Spameri\ElasticQuery\Collection\Item
{

	public function __construct(
		private string $field,
		private string $type,
	) {
	}


	public function key(): string
	{
		return 'order_' . $this->field . '_' . $this->type;
	}


	public function toArray(): array
	{
		return [
			$this->field => $this->type,
		];
	}

}
