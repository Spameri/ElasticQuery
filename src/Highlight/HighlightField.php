<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Highlight;


/**
 * Per-field highlight configuration.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/highlighting.html
 */
class HighlightField implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	/**
	 * @param array<int, string>|null $preTags
	 * @param array<int, string>|null $postTags
	 * @param array<int, string>|null $matchedFields
	 */
	public function __construct(
		private string $field,
		private string|null $type = null,
		private int|null $numberOfFragments = null,
		private int|null $fragmentSize = null,
		private string|null $boundaryScanner = null,
		private string|null $boundaryChars = null,
		private int|null $boundaryMaxScan = null,
		private string|null $boundaryScannerLocale = null,
		private string|null $encoder = null,
		private bool|null $forceSource = null,
		private string|null $fragmenter = null,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $highlightQuery = null,
		private array|null $matchedFields = null,
		private int|null $noMatchSize = null,
		private string|null $order = null,
		private int|null $phraseLimit = null,
		private bool|null $requireFieldMatch = null,
		private string|null $tagsSchema = null,
		private array|null $preTags = null,
		private array|null $postTags = null,
	)
	{
	}


	public function key(): string
	{
		return $this->field;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->type !== null) {
			$array['type'] = $this->type;
		}

		if ($this->numberOfFragments !== null) {
			$array['number_of_fragments'] = $this->numberOfFragments;
		}

		if ($this->fragmentSize !== null) {
			$array['fragment_size'] = $this->fragmentSize;
		}

		if ($this->boundaryScanner !== null) {
			$array['boundary_scanner'] = $this->boundaryScanner;
		}

		if ($this->boundaryChars !== null) {
			$array['boundary_chars'] = $this->boundaryChars;
		}

		if ($this->boundaryMaxScan !== null) {
			$array['boundary_max_scan'] = $this->boundaryMaxScan;
		}

		if ($this->boundaryScannerLocale !== null) {
			$array['boundary_scanner_locale'] = $this->boundaryScannerLocale;
		}

		if ($this->encoder !== null) {
			$array['encoder'] = $this->encoder;
		}

		if ($this->forceSource !== null) {
			$array['force_source'] = $this->forceSource;
		}

		if ($this->fragmenter !== null) {
			$array['fragmenter'] = $this->fragmenter;
		}

		if ($this->highlightQuery !== null) {
			$array['highlight_query'] = $this->highlightQuery->toArray();
		}

		if ($this->matchedFields !== null) {
			$array['matched_fields'] = $this->matchedFields;
		}

		if ($this->noMatchSize !== null) {
			$array['no_match_size'] = $this->noMatchSize;
		}

		if ($this->order !== null) {
			$array['order'] = $this->order;
		}

		if ($this->phraseLimit !== null) {
			$array['phrase_limit'] = $this->phraseLimit;
		}

		if ($this->requireFieldMatch !== null) {
			$array['require_field_match'] = $this->requireFieldMatch;
		}

		if ($this->tagsSchema !== null) {
			$array['tags_schema'] = $this->tagsSchema;
		}

		if ($this->preTags !== null) {
			$array['pre_tags'] = $this->preTags;
		}

		if ($this->postTags !== null) {
			$array['post_tags'] = $this->postTags;
		}

		return $array;
	}

}
