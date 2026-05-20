<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significanttext-aggregation.html
 */
class SignificantText implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, string>|null $sourceFields
	 */
	public function __construct(
		private string $field,
		private int|null $size = null,
		private bool $filterDuplicateText = false,
		private int|null $shardSize = null,
		private int|null $shardMinDocCount = null,
		private int|null $minDocCount = null,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $backgroundFilter = null,
		private array|null $sourceFields = null,
	)
	{
	}


	public function key(): string
	{
		return 'significant_text_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->filterDuplicateText === true) {
			$array['filter_duplicate_text'] = true;
		}

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->shardMinDocCount !== null) {
			$array['shard_min_doc_count'] = $this->shardMinDocCount;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->backgroundFilter !== null) {
			$array['background_filter'] = $this->backgroundFilter->toArray();
		}

		if ($this->sourceFields !== null) {
			$array['source_fields'] = $this->sourceFields;
		}

		return ['significant_text' => $array];
	}

}
