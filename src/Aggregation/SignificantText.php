<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significanttext-aggregation.html
 */
class SignificantText implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $size = null,
		private bool $filterDuplicateText = false,
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
		$array = [
			'field' => $this->field,
		];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->filterDuplicateText === true) {
			$array['filter_duplicate_text'] = true;
		}

		return [
			'significant_text' => $array,
		];
	}

}
