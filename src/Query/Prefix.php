<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-prefix-query.html
 */
class Prefix implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
		private bool|null $caseInsensitive = null,
	)
	{
	}


	public function key(): string
	{
		return 'prefix_' . $this->field . '_' . $this->query;
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

		return [
			'prefix' => [
				$this->field => $body,
			],
		];
	}

}
