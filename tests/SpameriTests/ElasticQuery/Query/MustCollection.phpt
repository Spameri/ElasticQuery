<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MustCollection extends \Tester\TestCase
{

	public function testEmptyCollection(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\MustCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testAddQuery(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\MustCollection();
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'active');

		$collection->add($term);

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue($term->key()));
	}


	public function testConstructorWithQueries(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('type', 'product');

		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term1, $term2);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testGetQuery(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term);

		$retrieved = $collection->get($term->key());

		\Tester\Assert::same($term, $retrieved);
	}


	public function testGetNonExistentReturnsNull(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\MustCollection();

		$result = $collection->get('non_existent_key');

		\Tester\Assert::null($result);
	}


	public function testRemoveQuery(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term);

		$removed = $collection->remove($term->key());

		\Tester\Assert::true($removed);
		\Tester\Assert::same(0, $collection->count());
	}


	public function testRemoveNonExistentReturnsFalse(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\MustCollection();

		$removed = $collection->remove('non_existent_key');

		\Tester\Assert::false($removed);
	}


	public function testIsValue(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term);

		\Tester\Assert::true($collection->isValue($term->key()));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testKeys(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('type', 'product');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term1, $term2);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains($term1->key(), $keys);
		\Tester\Assert::contains($term2->key(), $keys);
	}


	public function testClear(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('type', 'product');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term1, $term2);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testGetIterator(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('type', 'product');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term1, $term2);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(2, $iterator->count());
	}


	public function testIterateWithForeach(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('type', 'product');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection($term1, $term2);

		$count = 0;
		foreach ($collection as $query) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Query\LeafQueryInterface::class, $query);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testKeyUniqueness(): void
	{
		$term1 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$term2 = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$collection = new \Spameri\ElasticQuery\Query\MustCollection();

		$collection->add($term1);
		$collection->add($term2);

		\Tester\Assert::same(1, $collection->count());
	}


	public function testNestedCollections(): void
	{
		$queryCollection = new \Spameri\ElasticQuery\Query\QueryCollection('nested');
		$queryCollection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$collection = new \Spameri\ElasticQuery\Query\MustCollection($queryCollection);

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::type(\Spameri\ElasticQuery\Query\QueryCollection::class, $collection->get('nested'));
	}

}

(new MustCollection())->run();
