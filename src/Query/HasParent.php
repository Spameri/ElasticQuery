<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-parent-query.html
 */
class HasParent implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $parentType,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private bool|null $score = null,
		private bool|null $ignoreUnmapped = null,
	)
	{
	}


	public function key(): string
	{
		return 'has_parent_' . $this->parentType;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'parent_type' => $this->parentType,
			'query' => $this->query->toArray(),
		];

		if ($this->score !== null) {
			$body['score'] = $this->score;
		}

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'has_parent' => $body,
		];
	}

}
