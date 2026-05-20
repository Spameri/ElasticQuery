<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-shape-query.html
 */
class Shape implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed>|null $shape Inline GeoJSON shape; null when $indexedShape is used.
	 */
	public function __construct(
		private string $field,
		private array|null $shape = null,
		private string $relation = 'intersects',
		private bool|null $ignoreUnmapped = null,
		private \Spameri\ElasticQuery\Query\IndexedShape|null $indexedShape = null,
		private float $boost = 1.0,
	)
	{
		if ( ! \in_array($relation, ['intersects', 'disjoint', 'within', 'contains'], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Shape relation must be one of: intersects, disjoint, within, contains.',
			);
		}

		if ($shape === null && $indexedShape === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Shape requires either an inline shape or an indexedShape.',
			);
		}
	}


	public function key(): string
	{
		return 'shape_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$inner = [
			'relation' => $this->relation,
		];

		if ($this->shape !== null) {
			$inner['shape'] = $this->shape;
		}

		if ($this->indexedShape !== null) {
			$inner['indexed_shape'] = $this->indexedShape->toArray();
		}

		$body = [
			$this->field => $inner,
			'boost' => $this->boost,
		];

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'shape' => $body,
		];
	}

}
