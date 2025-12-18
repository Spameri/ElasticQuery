<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query\Match;

require_once __DIR__ . '/../../../bootstrap.php';


class Fuzziness extends \Tester\TestCase
{

	public function testAutoFuzziness(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO');

		\Tester\Assert::same('AUTO', $fuzziness->__toString());
	}


	public function testAutoWithDistanceRange(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO:3,6');

		\Tester\Assert::same('AUTO:3,6', $fuzziness->__toString());
	}


	public function testNumericFuzziness(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('0');

		\Tester\Assert::same('0', $fuzziness->__toString());
	}


	public function testNumericFuzzinessOne(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('1');

		\Tester\Assert::same('1', $fuzziness->__toString());
	}


	public function testNumericFuzzinessTwo(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('2');

		\Tester\Assert::same('2', $fuzziness->__toString());
	}


	public function testInvalidFuzzinessThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Match\Fuzziness('INVALID');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testInvalidTextThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Match\Fuzziness('fuzzy');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testAutoConstant(): void
	{
		\Tester\Assert::same('AUTO', \Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO);
	}


	public function testUsedInMultiMatch(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO');
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'search',
			1.0,
			$fuzziness,
		);

		$array = $multiMatch->toArray();

		\Tester\Assert::same('AUTO', $array['multi_match']['fuzziness']);
	}


	public function testUsedInElasticMatch(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('2');
		$match = new \Spameri\ElasticQuery\Query\ElasticMatch(
			'title',
			'search',
			1.0,
			$fuzziness,
		);

		$array = $match->toArray();

		\Tester\Assert::same('2', $array['match']['title']['fuzziness']);
	}

}

(new Fuzziness())->run();
