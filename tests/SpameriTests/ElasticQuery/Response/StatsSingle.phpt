<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class StatsSingle extends \Tester\TestCase
{

	public function testCreateFound(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(1, true);

		\Tester\Assert::same(1, $stats->version());
		\Tester\Assert::true($stats->found());
	}


	public function testCreateNotFound(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(0, false);

		\Tester\Assert::same(0, $stats->version());
		\Tester\Assert::false($stats->found());
	}


	public function testHigherVersion(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(5, true);

		\Tester\Assert::same(5, $stats->version());
		\Tester\Assert::true($stats->found());
	}

}

(new StatsSingle())->run();
