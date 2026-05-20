<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-parent-id-query.html
 */
class ParentId implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $type,
		private string $id,
		private bool|null $ignoreUnmapped = null,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'parent_id_' . $this->type . '_' . $this->id;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'type' => $this->type,
			'id' => $this->id,
			'boost' => $this->boost,
		];

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'parent_id' => $body,
		];
	}

}
