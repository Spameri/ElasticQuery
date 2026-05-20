<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * Field collapsing — group hits by a field's value.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/collapse-search-results.html
 */
class Collapse implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @param array<int, \Spameri\ElasticQuery\Query\InnerHits>|\Spameri\ElasticQuery\Query\InnerHits|null $innerHits
	 */
	public function __construct(
		private string $field,
		private array|\Spameri\ElasticQuery\Query\InnerHits|null $innerHits = null,
		private int|null $maxConcurrentGroupSearches = null,
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->innerHits !== null) {
			if (\is_array($this->innerHits)) {
				$array['inner_hits'] = [];
				foreach ($this->innerHits as $ih) {
					$array['inner_hits'][] = $ih->toArray();
				}
			} else {
				$array['inner_hits'] = $this->innerHits->toArray();
			}
		}

		if ($this->maxConcurrentGroupSearches !== null) {
			$array['max_concurrent_group_searches'] = $this->maxConcurrentGroupSearches;
		}

		return $array;
	}

}
