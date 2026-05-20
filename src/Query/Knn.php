<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * k-nearest neighbour vector similarity query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-knn-query.html
 */
class Knn implements LeafQueryInterface
{

	/**
	 * @param array<int, float> $queryVector
	 */
	public function __construct(
		private string $field,
		private array $queryVector,
		private int $k,
		private int $numCandidates,
		private float|null $similarity = null,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $filter = null,
		private float $boost = 1.0,
	)
	{
		if ($queryVector === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Knn query requires a non-empty queryVector.',
			);
		}

		if ($k < 1) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Knn k must be >= 1.',
			);
		}
	}


	public function key(): string
	{
		return 'knn_' . $this->field . '_' . $this->k;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'field' => $this->field,
			'query_vector' => $this->queryVector,
			'k' => $this->k,
			'num_candidates' => $this->numCandidates,
			'boost' => $this->boost,
		];

		if ($this->similarity !== null) {
			$body['similarity'] = $this->similarity;
		}

		if ($this->filter !== null) {
			$body['filter'] = $this->filter->toArray();
		}

		return [
			'knn' => $body,
		];
	}

}
