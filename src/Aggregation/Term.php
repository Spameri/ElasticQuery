<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
class Term implements LeafAggregationInterface
{

	private \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection $order;

	public function __construct(
		private string $field,
		private int $size = 0,
		private int|null $missing = null,
		\Spameri\ElasticQuery\Aggregation\Terms\OrderCollection|null $order = null,
		private string|null $include = null,
		private string|null $exclude = null,
		private string|null $key = null,
	)
	{
		$this->order = $order ?? new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();
	}


	public function key(): string
	{
		return $this->key ?? $this->field;
	}


	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->size > 0) {
			$array['size'] = $this->size;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if (\count($this->order)) {
			$array['order'] = $this->order->toArray();
		}

		if ($this->include !== null) {
			$array['include'] = $this->include;
		}

		if ($this->exclude !== null) {
			$array['exclude'] = $this->exclude;
		}

		return [
			'terms' => $array,
		];
	}

}
