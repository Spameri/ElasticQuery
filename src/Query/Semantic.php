<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Semantic query — queries a semantic_text field by inference.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-semantic-query.html
 */
class Semantic implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'semantic_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'semantic' => [
				'field' => $this->field,
				'query' => $this->query,
				'boost' => $this->boost,
			],
		];
	}

}
