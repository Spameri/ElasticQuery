<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-not-query.html
 */
class SpanNot implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $include,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $exclude,
		private int|null $pre = null,
		private int|null $post = null,
		private int|null $dist = null,
	)
	{
	}


	public function key(): string
	{
		return 'span_not_' . $this->include->key() . '_' . $this->exclude->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'include' => $this->include->toArray(),
			'exclude' => $this->exclude->toArray(),
		];

		if ($this->pre !== null) {
			$body['pre'] = $this->pre;
		}

		if ($this->post !== null) {
			$body['post'] = $this->post;
		}

		if ($this->dist !== null) {
			$body['dist'] = $this->dist;
		}

		return [
			'span_not' => $body,
		];
	}

}
