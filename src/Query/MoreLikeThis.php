<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-mlt-query.html
 */
class MoreLikeThis implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $fields
	 * @param array<int, string|array<string, mixed>> $like Texts or doc refs (['_index' => ..., '_id' => ...]).
	 * @param array<int, string|array<string, mixed>> $unlike
	 * @param array<int, string> $stopWords
	 */
	public function __construct(
		private array $fields,
		private array $like,
		private array $unlike = [],
		private int|null $minTermFreq = null,
		private int|null $maxQueryTerms = null,
		private int|string|null $minimumShouldMatch = null,
		private float|null $boostTerms = null,
		private bool|null $include = null,
		private int|null $minDocFreq = null,
		private int|null $maxDocFreq = null,
		private int|null $minWordLength = null,
		private int|null $maxWordLength = null,
		private array $stopWords = [],
		private string|null $analyzer = null,
		private float $boost = 1.0,
		private bool|null $failOnUnsupportedField = null,
	)
	{
		if ($fields === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'MoreLikeThis query requires at least one field.',
			);
		}

		if ($like === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'MoreLikeThis query requires at least one like value.',
			);
		}
	}


	public function key(): string
	{
		return 'more_like_this_' . \implode('-', $this->fields);
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'fields' => $this->fields,
			'like' => $this->like,
			'boost' => $this->boost,
		];

		if ($this->unlike !== []) {
			$body['unlike'] = $this->unlike;
		}

		if ($this->minTermFreq !== null) {
			$body['min_term_freq'] = $this->minTermFreq;
		}

		if ($this->maxQueryTerms !== null) {
			$body['max_query_terms'] = $this->maxQueryTerms;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->boostTerms !== null) {
			$body['boost_terms'] = $this->boostTerms;
		}

		if ($this->include !== null) {
			$body['include'] = $this->include;
		}

		if ($this->minDocFreq !== null) {
			$body['min_doc_freq'] = $this->minDocFreq;
		}

		if ($this->maxDocFreq !== null) {
			$body['max_doc_freq'] = $this->maxDocFreq;
		}

		if ($this->minWordLength !== null) {
			$body['min_word_length'] = $this->minWordLength;
		}

		if ($this->maxWordLength !== null) {
			$body['max_word_length'] = $this->maxWordLength;
		}

		if ($this->stopWords !== []) {
			$body['stop_words'] = $this->stopWords;
		}

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->failOnUnsupportedField !== null) {
			$body['fail_on_unsupported_field'] = $this->failOnUnsupportedField;
		}

		return [
			'more_like_this' => $body,
		];
	}

}
