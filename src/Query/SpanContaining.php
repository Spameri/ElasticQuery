<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-containing-query.html
 */
class SpanContaining implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $big,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $little,
	)
	{
	}


	public function key(): string
	{
		return 'span_containing_' . $this->big->key() . '_' . $this->little->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'span_containing' => [
				'big' => $this->big->toArray(),
				'little' => $this->little->toArray(),
			],
		];
	}

}
