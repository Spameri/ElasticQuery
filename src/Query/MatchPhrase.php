<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html
 */
class MatchPhrase implements LeafQueryInterface
{

	private ?string $analyzer = NULL;

	/**
	 * @param string|int|bool|null $query
	 */
	public function __construct(
		private string $field,
		private $query,
		private float $boost = 1.0,
		private int $slop = 0,
		string|null $analyzer = NULL,
	)
	{
		$this->analyzer = $analyzer;
	}


	public function changeAnalyzer(string $newAnalyzer): void
	{
		$this->analyzer = $newAnalyzer;
	}


	public function key(): string
	{
		return 'match_phrase_' . $this->field . '_' . (string) $this->query;
	}


	public function toArray(): array
	{
		$array = [
			'match_phrase' => [
				$this->field => [
					'query' => $this->query,
					'boost' => $this->boost,
				],
			],
		];

		if ($this->analyzer) {
			$array['match_phrase'][$this->field]['analyzer'] = $this->analyzer;
		}

		if ($this->slop) {
			$array['match_phrase'][$this->field]['slop'] = $this->slop;
		}

		return $array;
	}

}
