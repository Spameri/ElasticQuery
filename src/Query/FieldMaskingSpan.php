<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-field-masking-query.html
 */
class FieldMaskingSpan implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private string $field,
	)
	{
	}


	public function key(): string
	{
		return 'field_masking_span_' . $this->field . '_' . $this->query->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'field_masking_span' => [
				'query' => $this->query->toArray(),
				'field' => $this->field,
			],
		];
	}

}
