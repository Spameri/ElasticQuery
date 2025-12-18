<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore\ScoreFunction;

require_once __DIR__ . '/../../../bootstrap.php';


class FieldValueFactor extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('popularity');

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same([
			'field_value_factor' => [
				'field' => 'popularity',
				'factor' => 1.0,
				'modifier' => 'none',
				'missing' => 1.0,
			],
		], $array);
	}


	public function testToArrayWithFactor(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'likes',
			factor: 1.2,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same(1.2, $array['field_value_factor']['factor']);
	}


	public function testToArrayWithModifierLog(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'views',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('log', $array['field_value_factor']['modifier']);
	}


	public function testToArrayWithModifierLog1p(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'views',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG1P,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('log1p', $array['field_value_factor']['modifier']);
	}


	public function testToArrayWithModifierSqrt(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'rating',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_SQRT,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('sqrt', $array['field_value_factor']['modifier']);
	}


	public function testToArrayWithModifierSquare(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'score',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_SQUARE,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('square', $array['field_value_factor']['modifier']);
	}


	public function testToArrayWithModifierReciprocal(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'age',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_RECIPROCAL,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('reciprocal', $array['field_value_factor']['modifier']);
	}


	public function testToArrayWithMissing(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'boost_value',
			missing: 0.5,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same(0.5, $array['field_value_factor']['missing']);
	}


	public function testToArrayFullOptions(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'popularity',
			factor: 2.5,
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LN1P,
			missing: 0.0,
		);

		$array = $fieldValueFactor->toArray();

		\Tester\Assert::same('popularity', $array['field_value_factor']['field']);
		\Tester\Assert::same(2.5, $array['field_value_factor']['factor']);
		\Tester\Assert::same('ln1p', $array['field_value_factor']['modifier']);
		\Tester\Assert::same(0.0, $array['field_value_factor']['missing']);
	}


	public function testKey(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('boost_field');

		\Tester\Assert::same('field_value_factor_boost_field', $fieldValueFactor->key());
	}


	public function testFieldMethod(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('test_field');

		\Tester\Assert::same('test_field', $fieldValueFactor->field());
	}


	public function testFactorMethod(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'field',
			factor: 3.5,
		);

		\Tester\Assert::same(3.5, $fieldValueFactor->factor());
	}


	public function testModifierMethod(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'field',
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LN,
		);

		\Tester\Assert::same('ln', $fieldValueFactor->modifier());
	}


	public function testMissingMethod(): void
	{
		$fieldValueFactor = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'field',
			missing: 2.0,
		);

		\Tester\Assert::same(2.0, $fieldValueFactor->missing());
	}


	public function testConstants(): void
	{
		\Tester\Assert::same('none', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_NONE);
		\Tester\Assert::same('log', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG);
		\Tester\Assert::same('log1p', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG1P);
		\Tester\Assert::same('log2p', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG2P);
		\Tester\Assert::same('ln', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LN);
		\Tester\Assert::same('ln1p', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LN1P);
		\Tester\Assert::same('ln2p', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LN2P);
		\Tester\Assert::same('square', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_SQUARE);
		\Tester\Assert::same('sqrt', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_SQRT);
		\Tester\Assert::same('reciprocal', \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_RECIPROCAL);
	}

}

(new FieldValueFactor())->run();
