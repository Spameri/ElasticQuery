<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-multi-terms-aggregation.html
 */
class MultiTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, string> $terms
	 */
	public function __construct(
		private array $terms,
		private int|null $size = null,
		private string $key = 'multi_terms',
	)
	{
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
		$termsArray = [];
		foreach ($this->terms as $term) {
			$termsArray[] = ['field' => $term];
		}

		$array = [
			'terms' => $termsArray,
		];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		return [
			'multi_terms' => $array,
		];
	}

}
