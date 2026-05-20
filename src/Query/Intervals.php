<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-intervals-query.html
 */
class Intervals implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed> $rule e.g. ['match' => ['query' => 'my favorite food', 'max_gaps' => 0]]
	 *                                   or  ['all_of' => ['intervals' => [...], 'max_gaps' => 0]].
	 */
	public function __construct(
		private string $field,
		private array $rule,
	)
	{
		if ($rule === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Intervals query requires at least one rule.',
			);
		}
	}


	public function key(): string
	{
		return 'intervals_' . $this->field;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		return [
			'intervals' => [
				$this->field => $this->rule,
			],
		];
	}

}
