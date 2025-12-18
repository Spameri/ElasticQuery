<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class Stats extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\Stats(15, false, 100);

		\Tester\Assert::same(15, $stats->took());
		\Tester\Assert::false($stats->timedOut());
		\Tester\Assert::same(100, $stats->total());
	}


	public function testTimedOut(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\Stats(5000, true, 50);

		\Tester\Assert::same(5000, $stats->took());
		\Tester\Assert::true($stats->timedOut());
		\Tester\Assert::same(50, $stats->total());
	}


	public function testZeroResults(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\Stats(1, false, 0);

		\Tester\Assert::same(1, $stats->took());
		\Tester\Assert::false($stats->timedOut());
		\Tester\Assert::same(0, $stats->total());
	}

}

(new Stats())->run();
