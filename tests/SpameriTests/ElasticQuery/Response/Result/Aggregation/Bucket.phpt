<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result\Aggregation;

require_once __DIR__ . '/../../../../bootstrap.php';


class Bucket extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$bucket = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			'electronics',
			100,
			0,
		);

		\Tester\Assert::same('electronics', $bucket->key());
		\Tester\Assert::same(100, $bucket->docCount());
		\Tester\Assert::same(0, $bucket->position());
		\Tester\Assert::null($bucket->from());
		\Tester\Assert::null($bucket->to());
	}


	public function testWithRange(): void
	{
		$bucket = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			'0-100',
			50,
			0,
			0,
			100,
		);

		\Tester\Assert::same('0-100', $bucket->key());
		\Tester\Assert::same(50, $bucket->docCount());
		\Tester\Assert::same(0, $bucket->from());
		\Tester\Assert::same(100, $bucket->to());
	}


	public function testWithFloatRange(): void
	{
		$bucket = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			'price_range',
			25,
			0,
			10.5,
			99.99,
		);

		\Tester\Assert::same('price_range', $bucket->key());
		\Tester\Assert::same(25, $bucket->docCount());
		\Tester\Assert::same(10.5, $bucket->from());
		\Tester\Assert::same(99.99, $bucket->to());
	}


	public function testWithNullPosition(): void
	{
		$bucket = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			'test',
			10,
			null,
		);

		\Tester\Assert::same('test', $bucket->key());
		\Tester\Assert::same(10, $bucket->docCount());
		\Tester\Assert::null($bucket->position());
	}


	public function testOpenEndedRange(): void
	{
		$bucket = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket(
			'expensive',
			15,
			0,
			100,
			null,
		);

		\Tester\Assert::same(100, $bucket->from());
		\Tester\Assert::null($bucket->to());
	}

}

(new Bucket())->run();
