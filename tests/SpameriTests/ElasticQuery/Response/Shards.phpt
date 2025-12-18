<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class Shards extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(10, 8, 1, 1);

		\Tester\Assert::same(10, $shards->total());
		\Tester\Assert::same(8, $shards->successful());
		\Tester\Assert::same(1, $shards->skipped());
		\Tester\Assert::same(1, $shards->failed());
	}


	public function testAllSuccessful(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0);

		\Tester\Assert::same(5, $shards->total());
		\Tester\Assert::same(5, $shards->successful());
		\Tester\Assert::same(0, $shards->skipped());
		\Tester\Assert::same(0, $shards->failed());
	}


	public function testWithFailures(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(5, 3, 0, 2);

		\Tester\Assert::same(5, $shards->total());
		\Tester\Assert::same(3, $shards->successful());
		\Tester\Assert::same(2, $shards->failed());
	}

}

(new Shards())->run();
