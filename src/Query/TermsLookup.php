<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Terms lookup for the terms query — fetch values from a document field.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html#query-dsl-terms-lookup
 */
class TermsLookup implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private string $index,
		private string $id,
		private string $path,
		private string|null $routing = null,
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [
			'index' => $this->index,
			'id' => $this->id,
			'path' => $this->path,
		];

		if ($this->routing !== null) {
			$array['routing'] = $this->routing;
		}

		return $array;
	}

}
