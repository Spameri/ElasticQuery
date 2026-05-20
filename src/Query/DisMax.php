<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-dis-max-query.html
 */
class DisMax implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @var array<int, \Spameri\ElasticQuery\Query\LeafQueryInterface>
	 */
	private array $queries;


	public function __construct(
		\Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private float $tieBreaker = 0.0,
		private float $boost = 1.0,
	)
	{
		$this->queries = [$query];
	}


	public function addQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $query): void
	{
		$this->queries[] = $query;
	}


	public function key(): string
	{
		$keys = [];
		foreach ($this->queries as $query) {
			$keys[] = $query->key();
		}

		return 'dis_max_' . \implode('-', $keys);
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$queries = [];
		foreach ($this->queries as $query) {
			$queries[] = $query->toArray();
		}

		return [
			'dis_max' => [
				'queries' => $queries,
				'tie_breaker' => $this->tieBreaker,
				'boost' => $this->boost,
			],
		];
	}

}
