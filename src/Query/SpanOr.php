<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-or-query.html
 */
class SpanOr implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @var array<int, \Spameri\ElasticQuery\Query\LeafQueryInterface>
	 */
	private array $clauses;


	public function __construct(\Spameri\ElasticQuery\Query\LeafQueryInterface $clause)
	{
		$this->clauses = [$clause];
	}


	public function addClause(\Spameri\ElasticQuery\Query\LeafQueryInterface $clause): void
	{
		$this->clauses[] = $clause;
	}


	public function key(): string
	{
		$keys = [];
		foreach ($this->clauses as $clause) {
			$keys[] = $clause->key();
		}

		return 'span_or_' . \implode('-', $keys);
	}


	/**
	 * @return array<string, array<string, array<int, mixed>>>
	 */
	public function toArray(): array
	{
		$clauses = [];
		foreach ($this->clauses as $clause) {
			$clauses[] = $clause->toArray();
		}

		return [
			'span_or' => [
				'clauses' => $clauses,
			],
		];
	}

}
