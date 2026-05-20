<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-rate-aggregation.html
 */
class Rate implements LeafAggregationInterface
{

	public function __construct(
		private string|null $unit = null,
		private string|null $field = null,
		private string|null $mode = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string $key = 'rate',
	)
	{
		if ($unit === null && $script === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Rate requires unit (or script).',
			);
		}
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->unit !== null) {
			$array['unit'] = $this->unit;
		}

		if ($this->field !== null) {
			$array['field'] = $this->field;
		}

		if ($this->mode !== null) {
			$array['mode'] = $this->mode;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		return ['rate' => $array];
	}

}
