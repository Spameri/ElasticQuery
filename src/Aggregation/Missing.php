<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-missing-aggregation.html
 */
class Missing implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private \Spameri\ElasticQuery\Script|null $script = null,
	)
	{
	}


	public function key(): string
	{
		return 'missing_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		return ['missing' => $array];
	}

}
