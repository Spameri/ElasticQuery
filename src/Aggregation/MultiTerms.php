<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-multi-terms-aggregation.html
 */
class MultiTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, string|array<string, mixed>> $terms Either field names or {field, missing} objects.
	 * @param array<int, array<string, string>>|null $order
	 */
	public function __construct(
		private array $terms,
		private int|null $size = null,
		private string $key = 'multi_terms',
		private array|null $order = null,
		private int|null $minDocCount = null,
		private int|null $shardSize = null,
		private int|null $shardMinDocCount = null,
		private string|null $collectMode = null,
		private string|null $format = null,
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
			$termsArray[] = \is_array($term) ? $term : ['field' => $term];
		}

		$array = ['terms' => $termsArray];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->order !== null) {
			$array['order'] = $this->order;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->shardMinDocCount !== null) {
			$array['shard_min_doc_count'] = $this->shardMinDocCount;
		}

		if ($this->collectMode !== null) {
			$array['collect_mode'] = $this->collectMode;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['multi_terms' => $array];
	}

}
