<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class RangeValueCollection extends \Tester\TestCase
{

	public function testConstructorEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection();

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(0, $items);
	}


	public function testConstructorWithItems(): void
	{
		$rangeValue1 = new \Spameri\ElasticQuery\Aggregation\RangeValue('cheap', null, 50);
		$rangeValue2 = new \Spameri\ElasticQuery\Aggregation\RangeValue('expensive', 50, null);

		$collection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection($rangeValue1, $rangeValue2);

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(2, $items);
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection();
		$rangeValue = new \Spameri\ElasticQuery\Aggregation\RangeValue('test', 10, 20);

		$collection->add($rangeValue);

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(1, $items);
		\Tester\Assert::same($rangeValue, $items[0]);
	}


	public function testIterate(): void
	{
		$rangeValue1 = new \Spameri\ElasticQuery\Aggregation\RangeValue('first', null, 50);
		$rangeValue2 = new \Spameri\ElasticQuery\Aggregation\RangeValue('second', 50, 100);
		$rangeValue3 = new \Spameri\ElasticQuery\Aggregation\RangeValue('third', 100, null);

		$collection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection($rangeValue1, $rangeValue2, $rangeValue3);

		$keys = [];
		foreach ($collection as $item) {
			$keys[] = $item->key();
		}

		\Tester\Assert::same(['first', 'second', 'third'], $keys);
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection();

		\Tester\Assert::type(\ArrayIterator::class, $collection->getIterator());
	}

}

(new RangeValueCollection())->run();
