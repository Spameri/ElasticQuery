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
	)
	{
	}


	public function key(): string
	{
		return 'exits_' . $this->field;
	}


	public function toArray(): array
	{
		return [
			'exists' => [
				'field' => $this->field,
			],
		];
	}

}
