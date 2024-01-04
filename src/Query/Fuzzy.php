<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html
 */
class Fuzzy implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private bool|int|string|null $query,
		private float $boost = 1.0,
		private int $fuzziness = 2,
		private int $prefixLength = 0,
		private int $maxExpansion = 100,
	)
	{
	}


	public function key(): string
	{
		return 'fuzzy_' . $this->field . '_' . (string) $this->query;
	}


	public function toArray(): array
	{
		// phpcs:ignore SlevomatCodingStandard.Variables.UselessVariable
		$array = [
			'fuzzy' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
					'fuzziness' => $this->fuzziness,
					'prefix_length' => $this->prefixLength,
					'max_expansions' => $this->maxExpansion,
				],
			],
		];

		return $array;
	}

}
