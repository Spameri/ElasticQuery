<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-child-query.html
 */
class HasChild implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $type,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private string|null $scoreMode = null,
		private int|null $minChildren = null,
		private int|null $maxChildren = null,
		private bool|null $ignoreUnmapped = null,
	)
	{
	}


	public function key(): string
	{
		return 'has_child_' . $this->type;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'type' => $this->type,
			'query' => $this->query->toArray(),
		];

		if ($this->scoreMode !== null) {
			$body['score_mode'] = $this->scoreMode;
		}

		if ($this->minChildren !== null) {
			$body['min_children'] = $this->minChildren;
		}

		if ($this->maxChildren !== null) {
			$body['max_children'] = $this->maxChildren;
		}

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'has_child' => $body,
		];
	}

}
