<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
 */
class Exists implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'exits_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'exists' => [
				'field' => $this->field,
				'boost' => $this->boost,
			],
		];
	}

}
