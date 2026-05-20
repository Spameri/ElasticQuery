<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html
 */
class Nested implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public const SCORE_MODE_AVG = 'avg';
	public const SCORE_MODE_SUM = 'sum';
	public const SCORE_MODE_MIN = 'min';
	public const SCORE_MODE_MAX = 'max';
	public const SCORE_MODE_NONE = 'none';

	private \Spameri\ElasticQuery\Query\QueryCollection $query;


	public function __construct(
		private string $path,
		\Spameri\ElasticQuery\Query\QueryCollection|null $query = null,
		private string|null $scoreMode = null,
		private bool|null $ignoreUnmapped = null,
		private \Spameri\ElasticQuery\Query\InnerHits|null $innerHits = null,
	)
	{
		if ($query === null) {
			$query = new \Spameri\ElasticQuery\Query\QueryCollection();
		}

		$this->query = $query;
	}


	public function key(): string
	{
		return 'nested_' . $this->path;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$queryArray = $this->query->toArray();

		if (\count($queryArray) === 0) {
			$queryArray = ['bool' => new \stdClass()];
		}

		$body = [
			'path' => $this->path,
			'query' => $queryArray,
		];

		if ($this->scoreMode !== null) {
			$body['score_mode'] = $this->scoreMode;
		}

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		if ($this->innerHits !== null) {
			$body['inner_hits'] = $this->innerHits->toArray();
		}

		return [
			'nested' => $body,
		];
	}


	public function getQuery(): \Spameri\ElasticQuery\Query\QueryCollection
	{
		return $this->query;
	}

}
