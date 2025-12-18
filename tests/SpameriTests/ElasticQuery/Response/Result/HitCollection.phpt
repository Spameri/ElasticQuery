<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class HitCollection extends \Tester\TestCase
{

	public function testCreateEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\HitCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->ids());
	}


	public function testCreateWithHits(): void
	{
		$hit1 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test 1'],
			0,
			'test_index',
			'_doc',
			'id_1',
			1.0,
			1,
		);
		$hit2 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test 2'],
			1,
			'test_index',
			'_doc',
			'id_2',
			0.9,
			1,
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\HitCollection($hit1, $hit2);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testIds(): void
	{
		$hit1 = new \Spameri\ElasticQuery\Response\Result\Hit(
			[],
			0,
			'test_index',
			'_doc',
			'id_1',
			1.0,
			1,
		);
		$hit2 = new \Spameri\ElasticQuery\Response\Result\Hit(
			[],
			1,
			'test_index',
			'_doc',
			'id_2',
			0.9,
			1,
		);
		$hit3 = new \Spameri\ElasticQuery\Response\Result\Hit(
			[],
			2,
			'test_index',
			'_doc',
			'id_3',
			0.8,
			1,
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\HitCollection($hit1, $hit2, $hit3);

		\Tester\Assert::same(['id_1', 'id_2', 'id_3'], $collection->ids());
	}


	public function testIterate(): void
	{
		$hit1 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'First'],
			0,
			'test_index',
			'_doc',
			'id_1',
			1.0,
			1,
		);
		$hit2 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Second'],
			1,
			'test_index',
			'_doc',
			'id_2',
			0.9,
			1,
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\HitCollection($hit1, $hit2);

		$names = [];
		foreach ($collection as $hit) {
			$names[] = $hit->getValue('name');
		}

		\Tester\Assert::same(['First', 'Second'], $names);
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\HitCollection();

		\Tester\Assert::type(\ArrayIterator::class, $collection->getIterator());
	}

}

(new HitCollection())->run();
