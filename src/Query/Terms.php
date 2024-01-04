<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class Terms implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private array $query,
		private float $boost = 1.0,
	)
	{
		if ( ! \count($query)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Terms query must contain values, empty array given.',
			);
		}

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
