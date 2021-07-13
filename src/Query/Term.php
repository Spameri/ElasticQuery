<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class Term implements LeafQueryInterface
{

	private string $field;

	/**
	 * @var string|int|bool|float
	 */
	private $query;

	private float $boost;


	/**
	 * @param string|int|bool|float $query
	 */
	public function __construct(
		string $field
		, $query
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
		return [
			'term' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
				],
			],
		];
	}

}
