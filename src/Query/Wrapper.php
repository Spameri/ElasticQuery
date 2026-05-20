<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wrapper-query.html
 */
class Wrapper implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	private string $encoded;


	public function __construct(string $rawJsonQuery)
	{
		$this->encoded = \base64_encode($rawJsonQuery);
	}


	public function key(): string
	{
		return 'wrapper_' . \substr($this->encoded, 0, 12);
	}


	/**
	 * @return array<string, array<string, string>>
	 */
	public function toArray(): array
	{
		return [
			'wrapper' => [
				'query' => $this->encoded,
			],
		];
	}

}
