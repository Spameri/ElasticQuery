<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class ElasticMatch implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private bool|int|string|null $query,
		private float $boost = 1.0,
		private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = null,
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

	}


	public function changeAnalyzer(string $newAnalyzer): void
	{
		$this->analyzer = $newAnalyzer;
	}


	public function key(): string
	{
		return 'match_' . $this->field . '_' . (string) $this->query;
	}


	public function toArray(): array
	{
		$array = [
			'match' => [
				$this->field => [
					'query' => $this->query,
					'boost' => $this->boost,
				],
			],
		];

		if ($this->operator) {
			$array['match'][$this->field]['operator'] = $this->operator;
		}

		if ($this->fuzziness !== null) {
			$array['match'][$this->field]['fuzziness'] = $this->fuzziness->__toString();
		}

		if ($this->analyzer !== null) {
			$array['match'][$this->field]['analyzer'] = $this->analyzer;
		}

		if ($this->minimumShouldMatch !== null) {
			$array['match'][$this->field]['minimum_should_match'] = $this->minimumShouldMatch;
		}

		return $array;
	}

}
