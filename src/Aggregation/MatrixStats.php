<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-matrix-stats-aggregation.html
 */
class MatrixStats implements LeafAggregationInterface
{

	/**
	 * @param array<int, string> $fields
	 * @param array<string, float|int|string>|null $missing Per-field missing values.
	 */
	public function __construct(
		private array $fields,
		private array|null $missing = null,
		private string|null $mode = null,
		private string $key = 'matrix_stats',
	)
	{
		if ($fields === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'MatrixStats requires at least one field.',
			);
		}
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['fields' => $this->fields];

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->mode !== null) {
			$array['mode'] = $this->mode;
		}

		return ['matrix_stats' => $array];
	}

}
