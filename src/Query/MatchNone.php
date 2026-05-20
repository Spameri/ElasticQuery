<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-all-query.html#query-dsl-match-none-query
 */
class MatchNone implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function key(): string
	{
		return 'match_none';
	}


	/**
	 * @return array<string, \stdClass>
	 */
	public function toArray(): array
	{
		return [
			'match_none' => new \stdClass(),
		];
	}

}
