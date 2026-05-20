<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/inner-hits.html
 */
class InnerHits implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @param array<int, array<string, mixed>> $sort
	 * @param bool|array<int, string>|array<string, array<int, string>> $source
	 * @param array<string, mixed> $scriptFields
	 * @param array<int, string> $docvalueFields
	 * @param array<int, string> $storedFields
	 */
	public function __construct(
		private string|null $name = null,
		private int|null $from = null,
		private int|null $size = null,
		private array $sort = [],
		private bool|array $source = true,
		private \Spameri\ElasticQuery\Highlight|null $highlight = null,
		private bool|null $explain = null,
		private array $scriptFields = [],
		private array $docvalueFields = [],
		private bool|null $version = null,
		private bool|null $seqNoPrimaryTerm = null,
		private array $storedFields = [],
		private bool|null $trackScores = null,
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->name !== null) {
			$array['name'] = $this->name;
		}

		if ($this->from !== null) {
			$array['from'] = $this->from;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->sort !== []) {
			$array['sort'] = $this->sort;
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

		return $array;
	}

}
