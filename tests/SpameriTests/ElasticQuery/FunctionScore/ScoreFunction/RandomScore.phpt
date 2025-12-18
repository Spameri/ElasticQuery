<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore\ScoreFunction;

require_once __DIR__ . '/../../../bootstrap.php';


class RandomScore extends \Tester\TestCase
{

	public function testToArrayWithoutSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore();

		$array = $randomScore->toArray();

		\Tester\Assert::true(isset($array['random_score']));
		\Tester\Assert::type(\stdClass::class, $array['random_score']);
	}


	public function testToArrayWithSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('user_session_123');

		$array = $randomScore->toArray();

		\Tester\Assert::true(isset($array['random_score']));
		\Tester\Assert::same('user_session_123', $array['random_score']->seed);
	}


	public function testToArrayWithNumericSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('12345');

		$array = $randomScore->toArray();

		\Tester\Assert::same('12345', $array['random_score']->seed);
	}


	public function testKeyWithSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('my_seed');

		\Tester\Assert::same('random_my_seed', $randomScore->key());
	}


	public function testKeyWithoutSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore();

		\Tester\Assert::same('random_', $randomScore->key());
	}


	public function testSeedMethodWithSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('test_seed');

		\Tester\Assert::same('test_seed', $randomScore->seed());
	}


	public function testSeedMethodWithoutSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore();

		\Tester\Assert::null($randomScore->seed());
	}


	public function testToArrayEmptyObjectWhenNoSeed(): void
	{
		$randomScore = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore();

		$array = $randomScore->toArray();

		// When no seed, random_score should be empty stdClass
		$stdClass = $array['random_score'];
		\Tester\Assert::type(\stdClass::class, $stdClass);
		\Tester\Assert::same([], (array) $stdClass);
	}


	public function testConsistentResultsWithSameSeed(): void
	{
		$seed = 'consistent_seed_value';
		$randomScore1 = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore($seed);
		$randomScore2 = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore($seed);

		\Tester\Assert::same($randomScore1->toArray()['random_score']->seed, $randomScore2->toArray()['random_score']->seed);
	}


	public function testDifferentSeeds(): void
	{
		$randomScore1 = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed_a');
		$randomScore2 = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed_b');

		\Tester\Assert::notSame($randomScore1->key(), $randomScore2->key());
	}

}

(new RandomScore())->run();
