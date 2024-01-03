<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class ElasticMatch implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	private string $field;

	/**
	 * @var string|int|bool|null
	 */
	private $query;

	private string $operator;

	private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness;

	private float $boost;

	private string|null $analyzer;

	private int|null $minimumShouldMatch;


	/**
	 * @param string|int|bool|null $query
	 */
	public function __construct(
		string $field,
		$query,
		float $boost = 1.0,
		\Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = NULL,
		int|null $minimumShouldMatch = NULL,
		string $operator = \Spameri\ElasticQuery\Query\Match\Operator::OR,
		string|null $analyzer = NULL,
	)
	{
		if ( ! \in_array($operator, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $operator is invalid, see \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS for valid arguments.',
			);
		}

		$this->field = $field;
		$this->query = $query;
		$this->operator = $operator;
		$this->fuzziness = $fuzziness;
		$this->boost = $boost;
		$this->analyzer = $analyzer;
		$this->minimumShouldMatch = $minimumShouldMatch;
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

		if ($this->fuzziness !== NULL) {
			$array['match'][$this->field]['fuzziness'] = $this->fuzziness->__toString();
		}

		if ($this->analyzer !== NULL) {
			$array['match'][$this->field]['analyzer'] = $this->analyzer;
		}

		if ($this->minimumShouldMatch !== NULL) {
			$array['match'][$this->field]['minimum_should_match'] = $this->minimumShouldMatch;
		}

		return $array;
	}

}
