<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query\Match;

require_once __DIR__ . '/../../../bootstrap.php';


class Operator extends \Tester\TestCase
{

	public function testAndConstant(): void
	{
		\Tester\Assert::same('AND', \Spameri\ElasticQuery\Query\Match\Operator::AND);
	}


	public function testOrConstant(): void
	{
		\Tester\Assert::same('OR', \Spameri\ElasticQuery\Query\Match\Operator::OR);
	}


	public function testOperatorsArray(): void
	{
		$operators = \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS;

		\Tester\Assert::count(2, $operators);
		\Tester\Assert::same('AND', $operators['AND']);
		\Tester\Assert::same('OR', $operators['OR']);
	}


	public function testOperatorsContainsAnd(): void
	{
		\Tester\Assert::true(\in_array(\Spameri\ElasticQuery\Query\Match\Operator::AND, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, true));
	}


	public function testOperatorsContainsOr(): void
	{
		\Tester\Assert::true(\in_array(\Spameri\ElasticQuery\Query\Match\Operator::OR, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, true));
	}


	public function testUsedInMultiMatchWithAnd(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'search term',
			1.0,
			null,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
			null,
			\Spameri\ElasticQuery\Query\Match\Operator::AND,
		);

		$array = $multiMatch->toArray();

		\Tester\Assert::same('AND', $array['multi_match']['operator']);
	}


	public function testUsedInMultiMatchWithOr(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'search term',
			1.0,
			null,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
			null,
			\Spameri\ElasticQuery\Query\Match\Operator::OR,
		);

		$array = $multiMatch->toArray();

		\Tester\Assert::same('OR', $array['multi_match']['operator']);
	}


	public function testInvalidOperatorInMultiMatchThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\MultiMatch(
					['title'],
					'search',
					1.0,
					null,
					\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
					null,
					'INVALID_OPERATOR',
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}

}

(new Operator())->run();
