<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-script-score-query.html
 */
class ScriptScore implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed> $params
	 */
	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $query,
		private string $source,
		private string $lang = 'painless',
		private array $params = [],
		private float|null $minScore = null,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'script_score_' . $this->query->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$script = [
			'source' => $this->source,
			'lang' => $this->lang,
		];

		if ($this->params !== []) {
			$script['params'] = $this->params;
		}

		$body = [
			'query' => $this->query->toArray(),
			'script' => $script,
			'boost' => $this->boost,
		];

		if ($this->minScore !== null) {
			$body['min_score'] = $this->minScore;
		}

		return [
			'script_score' => $body,
		];
	}

}
