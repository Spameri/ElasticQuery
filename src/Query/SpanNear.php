<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-near-query.html
 */
class SpanNear implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @var array<int, \Spameri\ElasticQuery\Query\LeafQueryInterface>
	 */
	private array $clauses;


	public function __construct(
		\Spameri\ElasticQuery\Query\LeafQueryInterface $clause,
		private int $slop = 0,
		private bool $inOrder = true,
	)
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

		return 'span_near_' . \implode('-', $keys);
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$clauses = [];
		foreach ($this->clauses as $clause) {
			$clauses[] = $clause->toArray();
		}

		return [
			'span_near' => [
				'clauses' => $clauses,
				'slop' => $this->slop,
				'in_order' => $this->inOrder,
			],
		];
	}

}
