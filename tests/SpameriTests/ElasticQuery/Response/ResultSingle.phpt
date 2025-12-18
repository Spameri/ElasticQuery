<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class ResultSingle extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test Document', 'price' => 100],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(1, true);

		$result = new \Spameri\ElasticQuery\Response\ResultSingle($hit, $stats);

		\Tester\Assert::same($hit, $result->hit());
		\Tester\Assert::same($stats, $result->stats());
	}


	public function testHitValues(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test Document', 'price' => 100, 'active' => true],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(1, true);

		$result = new \Spameri\ElasticQuery\Response\ResultSingle($hit, $stats);

		\Tester\Assert::same('Test Document', $result->hit()->getValue('name'));
		\Tester\Assert::same(100, $result->hit()->getValue('price'));
		\Tester\Assert::same('doc_1', $result->hit()->id());
	}


	public function testStatsFound(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			[],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);
		$stats = new \Spameri\ElasticQuery\Response\StatsSingle(1, true);

		$result = new \Spameri\ElasticQuery\Response\ResultSingle($hit, $stats);

		\Tester\Assert::true($result->stats()->found());
		\Tester\Assert::same(1, $result->stats()->version());
	}

}

(new ResultSingle())->run();
