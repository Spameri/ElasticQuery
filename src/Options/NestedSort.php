<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * Nested sort sub-object for sorting on a nested field.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html#nested-sorting
 */
readonly class NestedSort implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		public string $path,
		public \Spameri\ElasticQuery\Query\LeafQueryInterface|null $filter = null,
		public int|null $maxChildren = null,
		public \Spameri\ElasticQuery\Options\NestedSort|null $nested = null,
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = ['path' => $this->path];

		if ($this->filter !== null) {
			$array['filter'] = $this->filter->toArray();
		}

		if ($this->maxChildren !== null) {
			$array['max_children'] = $this->maxChildren;
		}

		if ($this->nested !== null) {
			$array['nested'] = $this->nested->toArray();
		}

		return $array;
	}

}
