<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class ResultBulk extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\Stats(10, false, 2);
		$bulkActionCollection = new \Spameri\ElasticQuery\Response\Result\BulkActionCollection();

		$result = new \Spameri\ElasticQuery\Response\ResultBulk($stats, $bulkActionCollection);

		\Tester\Assert::same($stats, $result->stats());
	}


	public function testGetFirstAction(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(1, 1, 0, 0);
		$action1 = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'index',
			'test_index',
			'_doc',
			'id_1',
			1,
			'created',
			$shards,
			201,
			0,
			1,
		);
		$action2 = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'index',
			'test_index',
			'_doc',
			'id_2',
			1,
			'created',
			$shards,
			201,
			1,
			1,
		);

		$result = new \Spameri\ElasticQuery\Response\ResultBulk(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 2),
			new \Spameri\ElasticQuery\Response\Result\BulkActionCollection($action1, $action2),
		);

		$foundAction = $result->getFirstAction('id_2');

		\Tester\Assert::same($action2, $foundAction);
		\Tester\Assert::same('created', $foundAction->result());
	}


	public function testGetFirstActionNotFound(): void
	{
		$result = new \Spameri\ElasticQuery\Response\ResultBulk(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 0),
			new \Spameri\ElasticQuery\Response\Result\BulkActionCollection(),
		);

		\Tester\Assert::exception(
			static function () use ($result): void {
				$result->getFirstAction('nonexistent');
			},
			\Spameri\ElasticQuery\Exception\BulkActionNotFound::class,
		);
	}

}

(new ResultBulk())->run();
