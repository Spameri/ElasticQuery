<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;

class FunctionScore
{

	public const SCORE_MODE_MULTIPLY = 'multiply';
	public const SCORE_MODE_SUM = 'sum';
	public const SCORE_MODE_AVG = 'avg';
	public const SCORE_MODE_FIRST = 'first';
	public const SCORE_MODE_MAX = 'max';
	public const SCORE_MODE_MIN = 'min';

	private \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection $function;

	public function __construct(
		\Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection|null $function = null,
		private string|null $scoreMode = null,
	)
	{
		$this->function = $function ?? new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
	}


	public function function(): \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection
	{
		return $this->function;
	}


	public function scoreMode(): string|null
	{
		return $this->scoreMode;
	}


	public function toArray(array $queryPart): array
	{
		$functions = [];
		foreach ($this->function() as $function) {
			$functions[] = $function->toArray();
		}

		$array = [
			'function_score' => [
				'query' => $queryPart,
				'functions' => $functions,
			],
		];

		if ($this->scoreMode !== null) {
			$array['function_score']['score_mode'] = $this->scoreMode;
		}

		return $array;
	}

}
