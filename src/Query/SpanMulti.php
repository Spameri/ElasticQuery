<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-multi-term-query.html
 */
class SpanMulti implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $match,
	)
	{
	}


	public function key(): string
	{
		return 'span_multi_' . $this->match->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'span_multi' => [
				'match' => $this->match->toArray(),
			],
		];
	}

}
