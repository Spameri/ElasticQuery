<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html
 */
class Fuzzy implements LeafQueryInterface
{

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var string|int|bool|null
	 */
	private $query;

	/**
	 * @var float
	 */
	private $boost;

	/**
	 * @var int
	 */
	private $fuzziness;

	/**
	 * @var int
	 */
	private $prefixLength;

	/**
	 * @var int
	 */
	private $maxExpansion;


	/**
	 * @param string|int|bool|null $query
	 */
	public function __construct(
		string $field
		, $query
		, float $boost = 1.0
		, int $fuzziness = 2
		, int $prefixLength = 0
		, int $maxExpansion = 100,
	)
	{
		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
		$this->fuzziness = $fuzziness;
		$this->prefixLength = $prefixLength;
		$this->maxExpansion = $maxExpansion;
	}


	public function key(): string
	{
		return 'fuzzy_' . $this->field . '_' . (string) $this->query;
	}


	public function toArray(): array
	{
		// phpcs:ignore SlevomatCodingStandard.Variables.UselessVariable
		$array = [
			'fuzzy' => [
				$this->field => [
					'value' 			=> $this->query,
					'boost' 			=> $this->boost,
					'fuzziness' 		=> $this->fuzziness,
					'prefix_length' 	=> $this->prefixLength,
					'max_expansions' 	=> $this->maxExpansion,
				],
			],
		];

		return $array;
	}
}
