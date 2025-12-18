<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class BulkActionCollection extends \Tester\TestCase
{

	public function testCreateEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\BulkActionCollection();

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(0, $items);
	}


	public function testCreateWithActions(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 1, 0, 0);

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

		$collection = new \Spameri\ElasticQuery\Response\Result\BulkActionCollection($action1, $action2);

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(2, $items);
	}


	public function testIterate(): void
	{
		$shards = new \Spameri\ElasticQuery\Response\Shards(2, 1, 0, 0);

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
			'update',
			'test_index',
			'_doc',
			'id_2',
			2,
			'updated',
			$shards,
			200,
			1,
			1,
		);
		$action3 = new \Spameri\ElasticQuery\Response\Result\BulkAction(
			'delete',
			'test_index',
			'_doc',
			'id_3',
			1,
			'deleted',
			$shards,
			200,
			2,
			1,
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\BulkActionCollection(
			$action1,
			$action2,
			$action3,
		);

		$actions = [];
		$ids = [];
		foreach ($collection as $action) {
			$actions[] = $action->action();
			$ids[] = $action->id();
		}

		\Tester\Assert::same(['index', 'update', 'delete'], $actions);
		\Tester\Assert::same(['id_1', 'id_2', 'id_3'], $ids);
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\BulkActionCollection();

		\Tester\Assert::type(\ArrayIterator::class, $collection->getIterator());
	}

}

(new BulkActionCollection())->run();
