<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Sparse vector query (ELSER-style token weights).
 *
 * Use one of:
 *   - $inferenceId + $query (use a deployed inference endpoint to expand the text into tokens)
 *   - $queryVector (provide token => weight pairs directly)
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-sparse-vector-query.html
 */
class SparseVector implements LeafQueryInterface
{

	/**
	 * @param array<string, float>|null $queryVector Token => weight pairs.
	 * @param array<string, mixed>|null $pruningConfig
	 */
	public function __construct(
		private string $field,
		private string|null $inferenceId = null,
		private string|null $query = null,
		private array|null $queryVector = null,
		private bool|null $prune = null,
		private array|null $pruningConfig = null,
		private float $boost = 1.0,
	)
	{
		$hasInference = $inferenceId !== null && $query !== null;
		$hasVector = $queryVector !== null && $queryVector !== [];

		if ( ! $hasInference && ! $hasVector) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'SparseVector requires either (inferenceId + query) or queryVector.',
			);
		}
	}


	public function key(): string
	{
		return 'sparse_vector_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'field' => $this->field,
			'boost' => $this->boost,
		];

		if ($this->inferenceId !== null) {
			$body['inference_id'] = $this->inferenceId;
		}

		if ($this->query !== null) {
			$body['query'] = $this->query;
		}

		if ($this->queryVector !== null) {
			$body['query_vector'] = $this->queryVector;
		}

		if ($this->prune !== null) {
			$body['prune'] = $this->prune;
		}

		if ($this->pruningConfig !== null) {
			$body['pruning_config'] = $this->pruningConfig;
		}

		return [
			'sparse_vector' => $body,
		];
	}

}
