<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

class Max implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	private string $field;


	public function __construct(
		string $field,
	)
	{
		$this->field = $field;
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
