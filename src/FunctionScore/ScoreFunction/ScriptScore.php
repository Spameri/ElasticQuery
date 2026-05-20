<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction;


/**
 * Script-based scoring function inside function_score.
 *
 * Distinct from \Spameri\ElasticQuery\Query\ScriptScore, which is a top-level query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-script-score
 */
class ScriptScore implements \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Script $script,
		private string|null $name = null,
	)
	{
	}


	public function key(): string
	{
		return 'script_score_' . ($this->name ?? \spl_object_hash($this));
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'script_score' => [
				'script' => $this->script->toArray(),
			],
		];
	}

}
