<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Options
{

	private \Spameri\ElasticQuery\Options\SortCollection $sort;


	/**
	 * @param array<int|string, mixed>|null $searchAfter
	 * @param array<int, string>|null $storedFields
	 * @param array<int, string>|null $docvalueFields
	 * @param array<int, array<string, mixed>>|null $fields
	 * @param array<string, array<string, mixed>>|null $scriptFields
	 * @param array<string, mixed>|null $runtimeMappings
	 * @param array<int, \Spameri\ElasticQuery\Options\Suggest\SuggesterInterface>|null $suggesters
	 * @param array<int, \Spameri\ElasticQuery\Options\Rescore>|null $rescore
	 * @param array<int, array<string, float>>|null $indicesBoost
	 * @param array<int, string>|null $stats
	 * @param array<string, mixed>|null $ext
	 */
	public function __construct(
		private int|null $size = null,
		private int|null $from = null,
		\Spameri\ElasticQuery\Options\SortCollection|null $sort = null,
		private float|null $minScore = null,
		private bool $includeVersion = false,
		private string|null $scroll = null,
		private string|null $scrollId = null,
		private \Spameri\ElasticQuery\Options\Source|null $source = null,
		private bool|int|null $trackTotalHits = null,
		private bool|null $trackScores = null,
		private bool|null $explain = null,
		private int|null $terminateAfter = null,
		private string|null $timeout = null,
		private array|null $searchAfter = null,
		private \Spameri\ElasticQuery\Options\Pit|null $pit = null,
		private array|null $storedFields = null,
		private array|null $docvalueFields = null,
		private array|null $fields = null,
		private array|null $scriptFields = null,
		private array|null $runtimeMappings = null,
		private bool|null $seqNoPrimaryTerm = null,
		private array|null $indicesBoost = null,
		private \Spameri\ElasticQuery\Options\Collapse|null $collapse = null,
		private array|null $rescore = null,
		private array|null $suggesters = null,
		private string|null $suggestText = null,
		private bool|null $profile = null,
		private array|null $stats = null,
		private array|null $ext = null,
	)
	{
		$this->sort = $sort ?: new \Spameri\ElasticQuery\Options\SortCollection();
	}


	public function changeFrom(int $from): void
	{
		$this->from = $from;
	}


	public function changeSize(int $size): void
	{
		$this->size = $size;
	}


	public function sort(): \Spameri\ElasticQuery\Options\SortCollection
	{
		return $this->sort;
	}


	public function scroll(): string|null
	{
		return $this->scroll;
	}


	public function startScroll(
		string $scroll,
	): void
	{
		$this->scroll = $scroll;
	}


	public function scrollId(): string|null
	{
		return $this->scrollId;
	}


	public function scrollInitialized(
		string $scrollId,
	): void
	{
		$this->scrollId = $scrollId;
	}


	public function collapse(): \Spameri\ElasticQuery\Options\Collapse|null
	{
		return $this->collapse;
	}


	/**
	 * @return array<int, \Spameri\ElasticQuery\Options\Rescore>|null
	 */
	public function rescore(): array|null
	{
		return $this->rescore;
	}


	/**
	 * @return array<int, \Spameri\ElasticQuery\Options\Suggest\SuggesterInterface>|null
	 */
	public function suggesters(): array|null
	{
		return $this->suggesters;
	}


	public function suggestText(): string|null
	{
		return $this->suggestText;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->from !== null) {
			$array['from'] = $this->from;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		foreach ($this->sort as $item) {
			if ($item instanceof \Spameri\ElasticQuery\Options\Sort && $item->field === '_score') {
				$array['sort'][] = $item->field;
				continue;
			}

			$array['sort'][] = $item->toArray();
		}

		if ($this->minScore !== null) {
			$array['min_score'] = $this->minScore;
		}

		if ($this->includeVersion === true) {
			$array['version'] = $this->includeVersion;
		}

		if ($this->scrollId !== null) {
			$array['scroll_id'] = $this->scrollId;
			$array['scroll'] = $this->scroll;
		}

		if ($this->source !== null) {
			$array['_source'] = $this->source->value();
		}

		if ($this->trackTotalHits !== null) {
			$array['track_total_hits'] = $this->trackTotalHits;
		}

		if ($this->trackScores !== null) {
			$array['track_scores'] = $this->trackScores;
		}

		if ($this->explain !== null) {
			$array['explain'] = $this->explain;
		}

		if ($this->terminateAfter !== null) {
			$array['terminate_after'] = $this->terminateAfter;
		}

		if ($this->timeout !== null) {
			$array['timeout'] = $this->timeout;
		}

		if ($this->searchAfter !== null) {
			$array['search_after'] = $this->searchAfter;
		}

		if ($this->pit !== null) {
			$array['pit'] = $this->pit->toArray();
		}

		if ($this->storedFields !== null) {
			$array['stored_fields'] = $this->storedFields;
		}

		if ($this->docvalueFields !== null) {
			$array['docvalue_fields'] = $this->docvalueFields;
		}

		if ($this->fields !== null) {
			$array['fields'] = $this->fields;
		}

		if ($this->scriptFields !== null) {
			$array['script_fields'] = $this->scriptFields;
		}

		if ($this->runtimeMappings !== null) {
			$array['runtime_mappings'] = $this->runtimeMappings;
		}

		if ($this->seqNoPrimaryTerm !== null) {
			$array['seq_no_primary_term'] = $this->seqNoPrimaryTerm;
		}

		if ($this->indicesBoost !== null) {
			$array['indices_boost'] = $this->indicesBoost;
		}

		if ($this->profile !== null) {
			$array['profile'] = $this->profile;
		}

		if ($this->stats !== null) {
			$array['stats'] = $this->stats;
		}

		if ($this->ext !== null) {
			$array['ext'] = $this->ext;
		}

		return $array;
	}

}
