<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * Rescore the top N hits with a secondary query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/filter-search-results.html#rescore
 */
class Rescore implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public const SCORE_MODE_TOTAL = 'total';
	public const SCORE_MODE_MULTIPLY = 'multiply';
	public const SCORE_MODE_AVG = 'avg';
	public const SCORE_MODE_MAX = 'max';
	public const SCORE_MODE_MIN = 'min';

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private int $windowSize,
		private float|null $queryWeight = null,
		private float|null $rescoreQueryWeight = null,
		private string|null $scoreMode = null,
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$rescoreQuery = ['rescore_query' => $this->query->toArray()];

		if ($this->queryWeight !== null) {
			$rescoreQuery['query_weight'] = $this->queryWeight;
		}

		if ($this->rescoreQueryWeight !== null) {
			$rescoreQuery['rescore_query_weight'] = $this->rescoreQueryWeight;
		}

		if ($this->scoreMode !== null) {
			$rescoreQuery['score_mode'] = $this->scoreMode;
		}

		return [
			'window_size' => $this->windowSize,
			'query' => $rescoreQuery,
		];
	}

}
