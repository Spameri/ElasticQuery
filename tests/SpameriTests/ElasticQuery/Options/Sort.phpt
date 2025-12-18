<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class Sort extends \Tester\TestCase
{

	public function testToArrayDescending(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			'created_at',
			\Spameri\ElasticQuery\Options\Sort::DESC,
		);

		$array = $sort->toArray();

		\Tester\Assert::same([
			'created_at' => [
				'order' => 'DESC',
				'missing' => '_last',
			],
		], $array);
	}


	public function testToArrayAscending(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			'name',
			\Spameri\ElasticQuery\Options\Sort::ASC,
		);

		$array = $sort->toArray();

		\Tester\Assert::same([
			'name' => [
				'order' => 'ASC',
				'missing' => '_last',
			],
		], $array);
	}


	public function testToArrayMissingFirst(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			'price',
			\Spameri\ElasticQuery\Options\Sort::ASC,
			\Spameri\ElasticQuery\Options\Sort::MISSING_FIRST,
		);

		$array = $sort->toArray();

		\Tester\Assert::same([
			'price' => [
				'order' => 'ASC',
				'missing' => '_first',
			],
		], $array);
	}


	public function testToArrayMissingLast(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			'stock',
			\Spameri\ElasticQuery\Options\Sort::DESC,
			\Spameri\ElasticQuery\Options\Sort::MISSING_LAST,
		);

		$array = $sort->toArray();

		\Tester\Assert::same([
			'stock' => [
				'order' => 'DESC',
				'missing' => '_last',
			],
		], $array);
	}


	public function testKey(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort('field_name');

		\Tester\Assert::same('field_name', $sort->key());
	}


	public function testDefaultValues(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\Sort('default_field');

		$array = $sort->toArray();

		\Tester\Assert::same('DESC', $array['default_field']['order']);
		\Tester\Assert::same('_last', $array['default_field']['missing']);
	}


	public function testConstants(): void
	{
		\Tester\Assert::same('ASC', \Spameri\ElasticQuery\Options\Sort::ASC);
		\Tester\Assert::same('DESC', \Spameri\ElasticQuery\Options\Sort::DESC);
		\Tester\Assert::same('_last', \Spameri\ElasticQuery\Options\Sort::MISSING_LAST);
		\Tester\Assert::same('_first', \Spameri\ElasticQuery\Options\Sort::MISSING_FIRST);
	}


	public function testInvalidSortTypeThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Options\Sort('field', 'INVALID');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
			'Sorting type INVALID is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
		);
	}


	public function testInvalidMissingThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Options\Sort('field', \Spameri\ElasticQuery\Options\Sort::ASC, '_invalid');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
			'Sorting by missing value on filed field is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
		);
	}

}

(new Sort())->run();
