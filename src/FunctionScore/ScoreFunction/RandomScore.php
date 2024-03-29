<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction;

class RandomScore implements \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
{

	public function __construct(
		private string|null $seed = null,
	)
	{
	}


	public function key(): string
	{
		return 'random_' . $this->seed;
	}


	public function seed(): string|null
	{
		return $this->seed;
	}


	public function toArray(): array
	{
		$randomScore = new \stdClass();

		if ($this->seed) {
			$randomScore->seed = $this->seed;
		}

		return [
			'random_score' => $randomScore,
		];
	}

}
