<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html
 */
class MatchPhrase implements LeafQueryInterface
{

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var string|int|bool|null
	 */
	private $query;

	/**
	 * @var float
	 */
	private $boost;

	/**
	 * @var null|string
	 */
	private $analyzer;

	/**
	 * @var int
	 */
	private $slop;


	/**
	 * @param string|int|bool|null $query
	 */
	public function __construct(
		string $field
		, $query
		, float $boost = 1.0
		, int $slop = 0
		, ?string $analyzer = NULL
	)
	{
		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
		$this->analyzer = $analyzer;
		$this->slop = $slop;
	}


	public function key() : string
	{
		return 'match_phrase_' . $this->field . '_' . (string) $this->query;
	}


	public function toArray() : array
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
