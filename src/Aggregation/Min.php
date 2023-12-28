<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class Min implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	private string $field;


	public function __construct(
		string $field,
	) {
		$this->field = $field;
	}


	public function key(): string
	{
		return 'min_' . $this->field;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'min' => [
				'field' => $this->field,
			],
		];
	}

}
