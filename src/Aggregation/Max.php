<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class Max implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
	)
	{
	}


	public function key(): string
	{
		return 'max_' . $this->field;
	}


	/**
	 * @return array<string, array<string, string>>
	 */
	public function toArray(): array
	{
		return [
			'max' => [
				'field' => $this->field,
			],
		];
	}

}
