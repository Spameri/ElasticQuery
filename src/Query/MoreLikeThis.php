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
	 */
	public function __construct(
		private array $fields,
		private array $like,
		private array $unlike = [],
		private int|null $minTermFreq = null,
		private int|null $maxQueryTerms = null,
		private int|string|null $minimumShouldMatch = null,
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

		return [
			'more_like_this' => $body,
		];
	}

}
