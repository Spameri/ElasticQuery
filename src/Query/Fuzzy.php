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
		private bool|null $transpositions = null,
		private string|null $rewrite = null,
	)
	{
	}


	public function key(): string
	{
		return 'fuzzy_' . $this->field . '_' . (string) $this->query;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'value' => $this->query,
			'boost' => $this->boost,
			'fuzziness' => $this->fuzziness,
			'prefix_length' => $this->prefixLength,
			'max_expansions' => $this->maxExpansion,
		];

		if ($this->transpositions !== null) {
			$body['transpositions'] = $this->transpositions;
		}

		if ($this->rewrite !== null) {
			$body['rewrite'] = $this->rewrite;
		}

		return [
			'fuzzy' => [
				$this->field => $body,
			],
		];
	}

}
