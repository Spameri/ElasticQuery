<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html
 */
class TopHits implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param bool|array<int, string>|array<string, array<int, string>> $source
	 * @param array<string, mixed> $scriptFields Script-name => {script: {...}} map.
	 * @param array<int, string> $docvalueFields
	 * @param array<int, string> $storedFields
	 */
	public function __construct(
		private int $size,
		private int|null $from = null,
		private \Spameri\ElasticQuery\Options\SortCollection|null $sort = null,
		private bool|array $source = true,
		private \Spameri\ElasticQuery\Highlight|null $highlight = null,
		private bool|null $explain = null,
		private array $scriptFields = [],
		private array $docvalueFields = [],
		private bool|null $version = null,
		private bool|null $seqNoPrimaryTerm = null,
		private array $storedFields = [],
		private bool|null $trackScores = null,
		private string $key = 'top_hits',
	)
	{
	}


	public function key(): string
	{
		return $this->key . '_' . $this->size;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['size' => $this->size];

		if ($this->from !== null) {
			$array['from'] = $this->from;
		}

		if ($this->sort !== null && $this->sort->count() > 0) {
			$array['sort'] = $this->sort->toArray();
		}

		if ($this->source !== true) {
			$array['_source'] = $this->source;
		}

		if ($this->highlight !== null) {
			$array['highlight'] = $this->highlight->toArray();
		}

		if ($this->explain !== null) {
			$array['explain'] = $this->explain;
		}

		if ($this->scriptFields !== []) {
			$array['script_fields'] = $this->scriptFields;
		}

		if ($this->docvalueFields !== []) {
			$array['docvalue_fields'] = $this->docvalueFields;
		}

		if ($this->version !== null) {
			$array['version'] = $this->version;
		}

		if ($this->seqNoPrimaryTerm !== null) {
			$array['seq_no_primary_term'] = $this->seqNoPrimaryTerm;
		}

		if ($this->storedFields !== []) {
			$array['stored_fields'] = $this->storedFields;
		}

		if ($this->trackScores !== null) {
			$array['track_scores'] = $this->trackScores;
		}

		return ['top_hits' => $array];
	}

}
