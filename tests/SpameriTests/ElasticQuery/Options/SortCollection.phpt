<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class SortCollection extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();

		$array = $collection->toArray();

		\Tester\Assert::same([], $array);
	}


	public function testToArraySingleSort(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('created_at', \Spameri\ElasticQuery\Options\Sort::DESC));

		$array = $collection->toArray();

		\Tester\Assert::count(1, $array);
		\Tester\Assert::same([
			'created_at' => [
				'order' => 'DESC',
				'missing' => '_last',
			],
		], $array[0]);
	}


	public function testToArrayMultipleSorts(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('priority', \Spameri\ElasticQuery\Options\Sort::DESC));
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('name', \Spameri\ElasticQuery\Options\Sort::ASC));

		$array = $collection->toArray();

		\Tester\Assert::count(2, $array);
		\Tester\Assert::true(isset($array[0]['priority']));
		\Tester\Assert::true(isset($array[1]['name']));
	}


	public function testAddSort(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();

		\Tester\Assert::same(0, $collection->count());

		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field1'));

		\Tester\Assert::same(1, $collection->count());

		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field2'));

		\Tester\Assert::same(2, $collection->count());
	}


	public function testGetSort(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$sort = new \Spameri\ElasticQuery\Options\Sort('test_field');
		$collection->add($sort);

		$retrieved = $collection->get('test_field');

		\Tester\Assert::same($sort, $retrieved);
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();

		$retrieved = $collection->get('non_existent');

		\Tester\Assert::null($retrieved);
	}


	public function testRemoveSort(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('to_remove'));

		\Tester\Assert::same(1, $collection->count());

		$result = $collection->remove('to_remove');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('existing'));

		\Tester\Assert::true($collection->isValue('existing'));
		\Tester\Assert::false($collection->isValue('non_existing'));
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field1'));
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field2'));

		\Tester\Assert::same(2, $collection->count());

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('first'));
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('second'));

		$keys = $collection->keys();

		\Tester\Assert::same(['first', 'second'], $keys);
	}


	public function testIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('a'));
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('b'));

		$count = 0;
		foreach ($collection as $key => $sort) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Options\Sort::class, $sort);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testConstructorWithInitialSorts(): void
	{
		$sort1 = new \Spameri\ElasticQuery\Options\Sort('field1');
		$sort2 = new \Spameri\ElasticQuery\Options\Sort('field2');

		$collection = new \Spameri\ElasticQuery\Options\SortCollection($sort1, $sort2);

		\Tester\Assert::same(2, $collection->count());
		\Tester\Assert::notNull($collection->get('field1'));
		\Tester\Assert::notNull($collection->get('field2'));
	}


	public function testMixedSortTypes(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('name', \Spameri\ElasticQuery\Options\Sort::ASC));
		$collection->add(new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 48.8566,
			lon: 2.3522,
		));

		$array = $collection->toArray();

		\Tester\Assert::count(2, $array);
		\Tester\Assert::true(isset($array[0]['name']));
		\Tester\Assert::true(isset($array[1]['_geo_distance']));
	}


	public function testSameKeyReplaces(): void
	{
		$collection = new \Spameri\ElasticQuery\Options\SortCollection();
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field', \Spameri\ElasticQuery\Options\Sort::ASC));
		$collection->add(new \Spameri\ElasticQuery\Options\Sort('field', \Spameri\ElasticQuery\Options\Sort::DESC));

		\Tester\Assert::same(1, $collection->count());

		$array = $collection->toArray();

		\Tester\Assert::same('DESC', $array[0]['field']['order']);
	}

}

(new SortCollection())->run();
