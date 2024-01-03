<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class Terms implements LeafQueryInterface
{

	private string $field;

	/**
	 * @var array<string|int|bool|float>
	 */
	private array $query;

	private float $boost;


	public function __construct(
		string $field,
		array $query,
		float $boost = 1.0,
	)
	{
		if ( ! \count($query)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Terms query must contain values, empty array given.',
			);
		}

		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
	}


	public function key(): string
	{
		return 'terms_' . $this->field . '_' . \implode('-', $this->query);
	}


	public function toArray(): array
	{
		return [
			'terms' => [
				$this->field 	=> $this->query,
				'boost' 		=> $this->boost,
			],
		];
	}

}
