<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-rare-terms-aggregation.html
 */
class RareTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param string|array<int, string>|null $include
	 * @param string|array<int, string>|null $exclude
	 */
	public function __construct(
		private string $field,
		private int|null $maxDocCount = null,
		private float|null $precision = null,
		private string|array|null $include = null,
		private string|array|null $exclude = null,
		private string|null $missing = null,
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
		$array = ['field' => $this->field];

		if ($this->maxDocCount !== null) {
			$array['max_doc_count'] = $this->maxDocCount;
		}

		if ($this->precision !== null) {
			$array['precision'] = $this->precision;
		}

		if ($this->include !== null) {
			$array['include'] = $this->include;
		}

		if ($this->exclude !== null) {
			$array['exclude'] = $this->exclude;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		return ['rare_terms' => $array];
	}

}
