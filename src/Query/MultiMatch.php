<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatch implements LeafQueryInterface
{

	private array $fields;

	/**
	 * @var string|int|bool|null
	 */
	private $query;

	private string $type;

	private string $operator;

	private ?\Spameri\ElasticQuery\Query\Match\Fuzziness $fuzziness;

	private float $boost;

	private ?string $analyzer;

	private ?int $minimumShouldMatch;


	/**
	 * @param string|int|bool|null $query
	 */
	public function __construct(
		array $fields,
		$query,
		float $boost = 1.0,
		?\Spameri\ElasticQuery\Query\Match\Fuzziness $fuzziness = NULL,
		string $type = \Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
		?int $minimumShouldMatch = NULL,
		string $operator = \Spameri\ElasticQuery\Query\Match\Operator::OR,
		?string $analyzer = NULL
	)
	{
		if ( ! \in_array($operator, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $operator is invalid, see \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS for valid arguments.'
			);
		}
		if ( ! \in_array($type, \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $type is invalid, see \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES for valid arguments.'
			);
		}

		$this->fields = $fields;
		$this->query = $query;
		$this->type = $type;
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
