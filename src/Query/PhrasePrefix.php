<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

class PhrasePrefix implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	private string $field;

	private string $queryString;

	private int $boost;

	private int $slop;


	public function __construct(
		string $field,
		string $queryString,
		int $boost = 1,
		int $slop = 1,
	) {
		$this->field = $field;
		$this->queryString = $queryString;
		$this->boost = $boost;
		$this->slop = $slop;
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
