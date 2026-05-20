<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-regexp-query.html
 */
class Regexp implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
		private string|null $flags = null,
		private bool|null $caseInsensitive = null,
		private int|null $maxDeterminizedStates = null,
		private string|null $rewrite = null,
	)
	{
	}


	public function key(): string
	{
		return 'regexp_' . $this->field . '_' . $this->query;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'value' => $this->query,
			'boost' => $this->boost,
		];

		if ($this->flags !== null) {
			$body['flags'] = $this->flags;
		}

		if ($this->caseInsensitive !== null) {
			$body['case_insensitive'] = $this->caseInsensitive;
		}

		if ($this->maxDeterminizedStates !== null) {
			$body['max_determinized_states'] = $this->maxDeterminizedStates;
		}

		if ($this->rewrite !== null) {
			$body['rewrite'] = $this->rewrite;
		}

		return [
			'regexp' => [
				$this->field => $body,
			],
		];
	}

}
