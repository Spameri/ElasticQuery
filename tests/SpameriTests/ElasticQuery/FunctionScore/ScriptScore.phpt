<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore;

require_once __DIR__ . '/../../bootstrap.php';


class ScriptScore extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_function_score_script';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		$ss = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\ScriptScore(
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value * 2"),
		);

		$array = $ss->toArray();

		\Tester\Assert::same("doc['price'].value * 2", $array['script_score']['script']['source']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);

		$ss = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\ScriptScore(
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value * 2"),
		);

		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection($ss),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new ScriptScore())->run();
