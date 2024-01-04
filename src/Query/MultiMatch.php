<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatch implements LeafQueryInterface
{

	public function __construct(
		private array $fields,
		private bool|int|string|null $query,
		private float $boost = 1.0,
		private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = null,
		private string $type = \Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
		private int|null $minimumShouldMatch = null,
		private string $operator = \Spameri\ElasticQuery\Query\Match\Operator::OR,
		private string|null $analyzer = null,
	)
	{
		if ( ! \in_array($operator, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $operator is invalid, see \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS for valid arguments.',
			);
		}
		if ( ! \in_array($type, \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $type is invalid, see \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES for valid arguments.',
			);
		}

	}


	public function changeAnalyzer(string $newAnalyzer): void
	{
		$this->analyzer = $newAnalyzer;
	}


	public function key(): string
	{
		return 'multiMatch_' . \implode('-', $this->fields) . '_' . (string) $this->query;
	}


	public function toArray(): array
	{
		$array = [
			'multi_match' => [
				'query' => $this->query,
				'type' => $this->type,
				'fields' => $this->fields,
				'boost' => $this->boost,
			],
		];

		if ($this->operator) {
			$array['multi_match']['operator'] = $this->operator;
		}

		if ($this->fuzziness && $this->fuzziness->__toString()) {
			$array['multi_match']['fuzziness'] = $this->fuzziness->__toString();
		}

		if ($this->analyzer) {
			$array['multi_match']['analyzer'] = $this->analyzer;
		}

		if ($this->minimumShouldMatch) {
			$array['multi_match']['minimum_should_match'] = $this->minimumShouldMatch;
		}

		return $array;
	}

}
