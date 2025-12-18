<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

require_once __DIR__ . '/../bootstrap.php';


class FunctionScore extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore();

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::true(isset($array['function_score']));
		\Tester\Assert::same($queryPart, $array['function_score']['query']);
		\Tester\Assert::same([], $array['function_score']['functions']);
		\Tester\Assert::false(isset($array['function_score']['score_mode']));
	}


	public function testToArrayWithScoreMode(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MULTIPLY,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('multiply', $array['function_score']['score_mode']);
	}


	public function testToArrayWithScoreModeSum(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_SUM,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('sum', $array['function_score']['score_mode']);
	}


	public function testToArrayWithScoreModeAvg(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_AVG,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('avg', $array['function_score']['score_mode']);
	}


	public function testToArrayWithScoreModeFirst(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_FIRST,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('first', $array['function_score']['score_mode']);
	}


	public function testToArrayWithScoreModeMax(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MAX,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('max', $array['function_score']['score_mode']);
	}


	public function testToArrayWithScoreModeMin(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MIN,
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::same('min', $array['function_score']['score_mode']);
	}


	public function testScoreModeMethod(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_AVG,
		);

		\Tester\Assert::same('avg', $functionScore->scoreMode());
	}


	public function testScoreModeNullByDefault(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore();

		\Tester\Assert::null($functionScore->scoreMode());
	}


	public function testFunctionMethod(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore();

		$collection = $functionScore->function();

		\Tester\Assert::type(\Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection::class, $collection);
	}


	public function testConstructorWithFunctionScoreCollection(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('test_seed'));

		$functionScore = new \Spameri\ElasticQuery\FunctionScore($collection);

		\Tester\Assert::same(1, $functionScore->function()->count());
	}


	public function testToArrayWithFunctions(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore();
		$functionScore->function()->add(
			new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('popularity'),
		);

		$queryPart = ['match_all' => new \stdClass()];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::count(1, $array['function_score']['functions']);
		\Tester\Assert::true(isset($array['function_score']['functions'][0]['field_value_factor']));
	}


	public function testToArrayWithMultipleFunctions(): void
	{
		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_SUM,
		);
		$functionScore->function()->add(
			new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('popularity'),
		);
		$functionScore->function()->add(
			new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('abc123'),
		);

		$queryPart = ['match' => ['title' => 'test']];
		$array = $functionScore->toArray($queryPart);

		\Tester\Assert::count(2, $array['function_score']['functions']);
		\Tester\Assert::same('sum', $array['function_score']['score_mode']);
	}


	public function testConstants(): void
	{
		\Tester\Assert::same('multiply', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MULTIPLY);
		\Tester\Assert::same('sum', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_SUM);
		\Tester\Assert::same('avg', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_AVG);
		\Tester\Assert::same('first', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_FIRST);
		\Tester\Assert::same('max', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MAX);
		\Tester\Assert::same('min', \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MIN);
	}

}

(new FunctionScore())->run();
