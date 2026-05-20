<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html
 */
class WildCard implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
		private bool|null $caseInsensitive = null,
		private string|null $rewrite = null,
	)
	{
	}


	public function key(): string
	{
		return 'wildcard_' . $this->field . '_' . $this->query;
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

		if ($this->caseInsensitive !== null) {
			$body['case_insensitive'] = $this->caseInsensitive;
		}

		if ($this->rewrite !== null) {
			$body['rewrite'] = $this->rewrite;
		}

		return [
			'wildcard' => [
				$this->field => $body,
			],
		];
	}

}
