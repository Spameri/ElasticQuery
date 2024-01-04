<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
class Term implements LeafAggregationInterface
{

	private int|null $missing;

	private \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection $order;

	public function __construct(
		private string $field,
		private int $size = 0,
		int $missing = NULL,
		\Spameri\ElasticQuery\Aggregation\Terms\OrderCollection|null $order = NULL,
		private string|null $include = NULL,
		private string|null $exclude = NULL,
		private string|null $key = NULL,
	)
	{
		$this->missing = $missing;
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
			$array['size']  = $this->size;
		}

		if ($this->missing !== NULL) {
			$array['missing'] = $this->missing;
		}

		if (\count($this->order)) {
			$array['order'] = $this->order->toArray();
		}

		if ($this->include !== NULL) {
			$array['include'] = $this->include;
		}

		if ($this->exclude !== NULL) {
			$array['exclude'] = $this->exclude;
		}

		return [
			'terms' => $array,
		];
	}

}
