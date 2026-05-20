<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-rare-terms-aggregation.html
 */
class RareTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $maxDocCount = null,
		private float|null $precision = null,
	)
	{
	}


	public function key(): string
	{
		return 'rare_terms_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->maxDocCount !== null) {
			$array['max_doc_count'] = $this->maxDocCount;
		}

		if ($this->precision !== null) {
			$array['precision'] = $this->precision;
		}

		return [
			'rare_terms' => $array,
		];
	}

}
