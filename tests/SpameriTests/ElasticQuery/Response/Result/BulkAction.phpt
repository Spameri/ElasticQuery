<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class BulkAction extends \Tester\TestCase
{

	public function testCreateIndex(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 1, 0, 0);

		$bulkAction = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'index',
			'test_index',
			'_doc',
			'doc_1',
			1,
			'created',
			$shards,
			201,
			0,
			1,
		);

		\Tester\Assert::same('index', $bulkAction->action());
		\Tester\Assert::same('test_index', $bulkAction->index());
		\Tester\Assert::same('_doc', $bulkAction->type());
		\Tester\Assert::same('doc_1', $bulkAction->id());
		\Tester\Assert::same(1, $bulkAction->version());
		\Tester\Assert::same('created', $bulkAction->result());
		\Tester\Assert::same($shards, $bulkAction->shards());
		\Tester\Assert::same(201, $bulkAction->status());
		\Tester\Assert::same(0, $bulkAction->seqNo());
		\Tester\Assert::same(1, $bulkAction->primaryTerm());
	}


	public function testCreateUpdate(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 1, 0, 0);

		$bulkAction = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'update',
			'test_index',
			'_doc',
			'doc_1',
			2,
			'updated',
			$shards,
			200,
			1,
			1,
		);

		\Tester\Assert::same('update', $bulkAction->action());
		\Tester\Assert::same('updated', $bulkAction->result());
		\Tester\Assert::same(2, $bulkAction->version());
		\Tester\Assert::same(200, $bulkAction->status());
	}


	public function testCreateDelete(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 1, 0, 0);

		$bulkAction = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'delete',
			'test_index',
			'_doc',
			'doc_1',
			3,
			'deleted',
			$shards,
			200,
			2,
			1,
		);

		\Tester\Assert::same('delete', $bulkAction->action());
		\Tester\Assert::same('deleted', $bulkAction->result());
		\Tester\Assert::same(3, $bulkAction->version());
	}


	public function testShardsAccess(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 2, 0, 0);

		$bulkAction = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'index',
			'test_index',
			'_doc',
			'doc_1',
			1,
			'created',
			$shards,
			201,
			0,
			1,
		);

		\Tester\Assert::same(2, $bulkAction->shards()->total());
		\Tester\Assert::same(2, $bulkAction->shards()->successful());
		\Tester\Assert::same(0, $bulkAction->shards()->failed());
	}

}

(new BulkAction())->run();
