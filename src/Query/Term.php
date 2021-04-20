<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class Term implements LeafQueryInterface
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
	 * @var float
	 */
	private $boost;


	public function __construct(
		string $field
		, string $query
		, float $boost = 1.0
	)
	{
		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
	}


	public function key(): string
	{
		return 'term_' . $this->field . '_' . $this->query;
	}


	public function toArray(): array
	{
		// phpcs:ignore SlevomatCodingStandard.Variables.UselessVariable
		$array = [
			'term' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
				],
			],
		];

		return $array;
	}

}
