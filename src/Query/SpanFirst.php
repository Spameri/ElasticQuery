<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-first-query.html
 */
class SpanFirst implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $match,
		private int $end,
	)
	{
	}


	public function key(): string
	{
		return 'span_first_' . $this->match->key() . '_' . $this->end;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'span_first' => [
				'match' => $this->match->toArray(),
				'end' => $this->end,
			],
		];
	}

}
