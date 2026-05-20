<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-shape-query.html
 */
class Shape implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed> $shape
	 */
	public function __construct(
		private string $field,
		private array $shape,
		private string $relation = 'intersects',
		private bool|null $ignoreUnmapped = null,
	)
	{
		if ( ! \in_array($relation, ['intersects', 'disjoint', 'within', 'contains'], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Shape relation must be one of: intersects, disjoint, within, contains.',
			);
		}
	}


	public function key(): string
	{
		return 'shape_' . $this->field;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$inner = [
			'shape' => $this->shape,
			'relation' => $this->relation,
		];

		if ($this->ignoreUnmapped !== null) {
			$inner['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'shape' => [
				$this->field => $inner,
			],
		];
	}

}
