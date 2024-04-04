<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

class PhrasePrefix implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $queryString,
		private int $boost = 1,
		private int $slop = 1,
	)
	{
	}


	public function key(): string
	{
		return 'phrase_prefix_' . $this->field . '_' . $this->queryString;
	}


	public function toArray(): array
	{
		return [
			'match_phrase_prefix' => [
				$this->field => [
					'query' => $this->queryString,
					'boost' => $this->boost,
					'slop' => $this->slop,
				],
			],
		];
	}

}
