<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-query.html
 */
class GeoShape implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed> $shape GeoJSON-style shape, e.g.
	 *                                    ['type' => 'envelope', 'coordinates' => [[13, 53], [14, 52]]].
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
				'GeoShape relation must be one of: intersects, disjoint, within, contains.',
			);
		}
	}


	public function key(): string
	{
		return 'geo_shape_' . $this->field;
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
			'geo_shape' => [
				$this->field => $inner,
			],
		];
	}

}
