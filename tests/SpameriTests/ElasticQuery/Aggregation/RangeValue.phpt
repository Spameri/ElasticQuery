<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class RangeValue extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', 10, 20);

		$array = $rangeValue->toArray();

		\Tester\Assert::same('test', $array['key']);
		\Tester\Assert::same(10, $array['from']);
		\Tester\Assert::same(21, $array['to']);
	}


	public function testToArrayWithNullFrom(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('cheap', null, 50);

		$array = $rangeValue->toArray();

		\Tester\Assert::same('cheap', $array['key']);
		\Tester\Assert::null($array['from']);
		\Tester\Assert::same(51, $array['to']);
	}


	public function testToArrayWithNullTo(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('expensive', 100, null);

		$array = $rangeValue->toArray();

		\Tester\Assert::same('expensive', $array['key']);
		\Tester\Assert::same(100, $array['from']);
		\Tester\Assert::null($array['to']);
	}


	public function testToArrayWithFromNotEqual(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', 10, 20, false, true);

		$array = $rangeValue->toArray();

		\Tester\Assert::same(11, $array['from']);
		\Tester\Assert::same(21, $array['to']);
	}


	public function testToArrayWithToNotEqual(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', 10, 20, true, false);

		$array = $rangeValue->toArray();

		\Tester\Assert::same(10, $array['from']);
		\Tester\Assert::same(20, $array['to']);
	}


	public function testToArrayWithFloatValues(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', 10.5, 20.5);

		$array = $rangeValue->toArray();

		\Tester\Assert::same(10.5, $array['from']);
		\Tester\Assert::same(20.5, $array['to']);
	}


	public function testToArrayWithStringValues(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', '2023-01-01', '2023-12-31');

		$array = $rangeValue->toArray();

		\Tester\Assert::same('2023-01-01', $array['from']);
		\Tester\Assert::same('2023-12-31', $array['to']);
	}


	public function testKey(): void
	{
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('my_range', 10, 20);

		\Tester\Assert::same('my_range', $rangeValue->key());
	}

}

(new RangeValue())->run();
