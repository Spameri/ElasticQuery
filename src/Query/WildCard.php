<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html
 */
class WildCard implements LeafQueryInterface
{

	private string $field;

	private string $query;

	private float $boost;


	public function __construct(
		string $field
		, string $query
		, float $boost = 1.0,
	)
	{
		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
	}


	public function key(): string
	{
		return 'wildcard_' . $this->field . '_' . $this->query;
	}


	public function toArray(): array
	{
		return [
			'wildcard' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
				],
			],
		];
	}

}
