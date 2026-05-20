<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-categorize-text-aggregation.html
 */
class CategorizeText implements LeafAggregationInterface
{

	/**
	 * @param array<int, string>|null $categorizationFilters
	 */
	public function __construct(
		private string $field,
		private int|null $maxUniqueTokens = null,
		private int|null $maxMatchedTokens = null,
		private float|null $similarityThreshold = null,
		private array|null $categorizationFilters = null,
		private int|null $shardSize = null,
		private int|null $size = null,
		private int|null $minDocCount = null,
		private int|null $shardMinDocCount = null,
	)
	{
	}


	public function key(): string
	{
		return 'categorize_text_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->maxUniqueTokens !== null) {
			$array['max_unique_tokens'] = $this->maxUniqueTokens;
		}

		if ($this->maxMatchedTokens !== null) {
			$array['max_matched_tokens'] = $this->maxMatchedTokens;
		}

		if ($this->similarityThreshold !== null) {
			$array['similarity_threshold'] = $this->similarityThreshold;
		}

		if ($this->categorizationFilters !== null) {
			$array['categorization_filters'] = $this->categorizationFilters;
		}

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->shardMinDocCount !== null) {
			$array['shard_min_doc_count'] = $this->shardMinDocCount;
		}

		return ['categorize_text' => $array];
	}

}
