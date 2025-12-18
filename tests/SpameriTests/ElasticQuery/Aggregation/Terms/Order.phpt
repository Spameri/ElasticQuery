<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation\Terms;

require_once __DIR__ . '/../../../bootstrap.php';


class Order extends \Tester\TestCase
{

	public function testToArrayCountDesc(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');

		$array = $order->toArray();

		\Tester\Assert::same(['_count' => 'desc'], $array);
	}


	public function testToArrayCountAsc(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'asc');

		$array = $order->toArray();

		\Tester\Assert::same(['_count' => 'asc'], $array);
	}


	public function testToArrayKeyDesc(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_key', 'desc');

		$array = $order->toArray();

		\Tester\Assert::same(['_key' => 'desc'], $array);
	}


	public function testToArrayCustomField(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('avg_price', 'asc');

		$array = $order->toArray();

		\Tester\Assert::same(['avg_price' => 'asc'], $array);
	}


	public function testKey(): void
	{
		$order = new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc');

		\Tester\Assert::same('order__count_desc', $order->key());
	}

}

(new Order())->run();
