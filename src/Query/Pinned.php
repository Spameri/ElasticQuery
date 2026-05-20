<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-pinned-query.html
 */
class Pinned implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $ids   IDs to pin (use either $ids or $docs).
	 * @param array<int, array<string, string>> $docs Document references: [['_index' => ..., '_id' => ...]].
	 */
	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $organic,
		private array $ids = [],
		private array $docs = [],
	)
	{
		if ($ids === [] && $docs === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Pinned query requires either ids or docs to pin.',
			);
		}
	}


	public function key(): string
	{
		return 'pinned_' . $this->organic->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'organic' => $this->organic->toArray(),
		];

		if ($this->ids !== []) {
			$body['ids'] = $this->ids;
		}

		if ($this->docs !== []) {
			$body['docs'] = $this->docs;
		}

		return [
			'pinned' => $body,
		];
	}

}
