<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significantterms-aggregation.html
 */
class SignificantTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $size = null,
		private int|null $minDocCount = null,
	)
	{
	}


	public function key(): string
	{
		return 'significant_terms_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		return [
			'significant_terms' => $array,
		];
	}

}
