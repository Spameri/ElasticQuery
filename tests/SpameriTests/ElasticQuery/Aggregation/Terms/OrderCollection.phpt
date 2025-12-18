<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation\Terms;

require_once __DIR__ . '/../../../bootstrap.php';


class OrderCollection extends \Tester\TestCase
{

	public function testConstructorEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testConstructorWithItems(): void
	{
		$order1 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$order2 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_key', 'asc');

		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order1, $order2);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');

		$collection->add($order);

		\Tester\Assert::same(1, $collection->count());
	}


	public function testGet(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order);

		$result = $collection->get($order->key());

		\Tester\Assert::same($order, $result);
	}


	public function testGetNull(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();

		$result = $collection->get('nonexistent');

		\Tester\Assert::null($result);
	}


	public function testRemove(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order);

		$result = $collection->remove($order->key());

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
	}


	public function testRemoveNonexistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();

		$result = $collection->remove('nonexistent');

		\Tester\Assert::false($result);
	}


	public function testIsValue(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order);

		\Tester\Assert::true($collection->isValue($order->key()));
		\Tester\Assert::false($collection->isValue('nonexistent'));
	}


	public function testKeys(): void
	{
		$order1 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$order2 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_key', 'asc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order1, $order2);

		$keys = $collection->keys();

		\Tester\Assert::contains($order1->key(), $keys);
		\Tester\Assert::contains($order2->key(), $keys);
	}


	public function testClear(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testToArraySingleItem(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order);

		$array = $collection->toArray();

		\Tester\Assert::same(['_count' => 'desc'], $array);
	}


	public function testToArrayMultipleItems(): void
	{
		$order1 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$order2 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_key', 'asc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order1, $order2);

		$array = $collection->toArray();

		\Tester\Assert::count(2, $array);
		\Tester\Assert::same(['_count' => 'desc'], $array[0]);
		\Tester\Assert::same(['_key' => 'asc'], $array[1]);
	}


	public function testIterate(): void
	{
		$order1 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');
		$order2 = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_key', 'asc');
		$collection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection($order1, $order2);

		$count = 0;
		foreach ($collection as $order) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Aggregation\Terms\Order::class, $order);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}

}

(new OrderCollection())->run();
