<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

class Nested implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	private \Spameri\ElasticQuery\Query\QueryCollection $query;


	public function __construct(
		private string $path,
		\Spameri\ElasticQuery\Query\QueryCollection|null $query = NULL,
	) {

		if ($query === NULL) {
			$query = new \Spameri\ElasticQuery\Query\QueryCollection();
		}

		$this->query = $query;
	}


	public function key(): string
	{
		return 'nested_' . $this->path;
	}


	public function toArray(): array
	{
		return [
			'nested' => [
				'path' => $this->path,
				'query' => [
					'bool' => $this->query->toArray(),
				],
			],
		];
	}


	public function getQuery(): \Spameri\ElasticQuery\Query\QueryCollection
	{
		return $this->query;
	}

}
