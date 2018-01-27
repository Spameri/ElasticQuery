<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class Match extends AbstractLeafQuery
{

	/**
	 * @var string
	 */
	private $field;
	/**
	 * @var string
	 */
	private $query;
	/**
	 * @var string
	 */
	private $operator;
	/**
	 * @var null|\Spameri\ElasticQuery\Query\Match\Fuzziness
	 */
	private $fuzziness;
	/**
	 * @var float
	 */
	private $boost;
	/**
	 * @var int|null
	 */
	private $slop;
	/**
	 * @var null|string
	 */
	private $analyzer;
	/**
	 * @var int|null
	 */
	private $minimumShouldMatch;


	public function __construct(
		string $field,
		string $query,
		float $boost = 1.0,
		string $operator = \Spameri\ElasticQuery\Query\Match\Operator::OR,
		?int $slop = NULL,
		?\Spameri\ElasticQuery\Query\Match\Fuzziness $fuzziness = NULL,
		?string $analyzer = NULL,
		?int $minimumShouldMatch = NULL
	)
	{
		if ( ! \in_array($operator, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $operator is invalid, see \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS for valid arguments.'
			);
		}

		$this->field = $field;
		$this->query = $query;
		$this->operator = $operator;
		$this->fuzziness = $fuzziness;
		$this->boost = $boost;
		$this->slop = $slop;
		$this->analyzer = $analyzer;
		$this->minimumShouldMatch = $minimumShouldMatch;
	}


	public function key() : string
	{
		return $this->field . '_' . $this->query;
	}


	public function toArray() : array
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

		if ($this->fuzziness) {
			$array['match'][$this->field]['fuzziness'] = $this->fuzziness;
		}

		if ($this->slop) {
			$array['match'][$this->field]['slop'] = $this->slop;
		}

		if ($this->analyzer) {
			$array['match'][$this->field]['analyzer'] = $this->analyzer;
		}

		if ($this->minimumShouldMatch) {
			$array['match'][$this->field]['minimum_should_match'] = $this->minimumShouldMatch;
		}

		return $array;
	}

}
