<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore;

require_once __DIR__ . '/../../bootstrap.php';


class FunctionScoreCollection extends \Tester\TestCase
{

	public function testEmptyCollection(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testAddSingleItem(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('test'));

		\Tester\Assert::same(1, $collection->count());
	}


	public function testAddMultipleItems(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed1'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('popularity'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(
			2.0,
			new \Spameri\ElasticQuery\Query\Term('featured', true),
		));

		\Tester\Assert::same(3, $collection->count());
	}


	public function testConstructorWithItems(): void
	{
		$random = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed');
		$fieldValue = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('views');

		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection($random, $fieldValue);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testGet(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$fieldValue = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('likes');
		$collection->add($fieldValue);

		$retrieved = $collection->get('field_value_factor_likes');

		\Tester\Assert::same($fieldValue, $retrieved);
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();

		$retrieved = $collection->get('non_existent');

		\Tester\Assert::null($retrieved);
	}


	public function testRemove(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('to_remove'));

		\Tester\Assert::same(1, $collection->count());

		$result = $collection->remove('random_to_remove');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('rating'));

		\Tester\Assert::true($collection->isValue('field_value_factor_rating'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed1'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('popularity'));

		$keys = $collection->keys();

		\Tester\Assert::contains('random_seed1', $keys);
		\Tester\Assert::contains('field_value_factor_popularity', $keys);
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('seed1'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('views'));

		\Tester\Assert::same(2, $collection->count());

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('a'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor('b'));

		$count = 0;
		foreach ($collection as $key => $item) {
			\Tester\Assert::type(\Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface::class, $item);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testSameKeyReplaces(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'popularity',
			factor: 1.0,
		));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'popularity',
			factor: 2.0,
		));

		\Tester\Assert::same(1, $collection->count());

		$retrieved = $collection->get('field_value_factor_popularity');
		\Tester\Assert::same(2.0, $retrieved->factor());
	}


	public function testMixedFunctionTypes(): void
	{
		$collection = new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection();

		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore('user_123'));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
			field: 'likes',
			factor: 1.2,
			modifier: \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor::MODIFIER_LOG1P,
		));
		$collection->add(new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(
			3.0,
			new \Spameri\ElasticQuery\Query\Term('premium', true),
		));

		\Tester\Assert::same(3, $collection->count());

		// Verify each type is retrievable
		\Tester\Assert::notNull($collection->get('random_user_123'));
		\Tester\Assert::notNull($collection->get('field_value_factor_likes'));
		\Tester\Assert::notNull($collection->get('weight_term_premium_1'));
	}

}

(new FunctionScoreCollection())->run();
