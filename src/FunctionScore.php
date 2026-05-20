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

	public const BOOST_MODE_MULTIPLY = 'multiply';
	public const BOOST_MODE_REPLACE = 'replace';
	public const BOOST_MODE_SUM = 'sum';
	public const BOOST_MODE_AVG = 'avg';
	public const BOOST_MODE_MAX = 'max';
	public const BOOST_MODE_MIN = 'min';

	private \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection $function;

	public function __construct(
		\Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection|null $function = null,
		private string|null $scoreMode = null,
		private string|null $boostMode = null,
		private float|null $boost = null,
		private float|null $maxBoost = null,
		private float|null $minScore = null,
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


	/**
	 * @param array<string, mixed> $queryPart
	 * @return array<string, array<string, mixed>>
	 */
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

		if ($this->boostMode !== null) {
			$array['function_score']['boost_mode'] = $this->boostMode;
		}

		if ($this->boost !== null) {
			$array['function_score']['boost'] = $this->boost;
		}

		if ($this->maxBoost !== null) {
			$array['function_score']['max_boost'] = $this->maxBoost;
		}

		if ($this->minScore !== null) {
			$array['function_score']['min_score'] = $this->minScore;
		}

		return $array;
	}

}
