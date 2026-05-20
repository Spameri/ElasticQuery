<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Indexed-shape lookup for geo_shape / shape queries — references a pre-indexed shape document.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-query.html#_pre_indexed_shape
 */
class IndexedShape implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private string $id,
		private string $index,
		private string $path,
		private string|null $routing = null,
	)
	{
	}


	/**
	 * @return array<string, string>
	 */
	public function toArray(): array
	{
		$array = [
			'id' => $this->id,
			'index' => $this->index,
			'path' => $this->path,
		];

		if ($this->routing !== null) {
			$array['routing'] = $this->routing;
		}

		return $array;
	}

}
