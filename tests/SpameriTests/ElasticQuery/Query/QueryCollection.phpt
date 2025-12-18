<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class QueryCollection extends \Tester\TestCase
{

	public function testEmptyCollection(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();

		$array = $collection->toArray();

		\Tester\Assert::same([], $array);
	}


	public function testWithMustQueries(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('status', 'active'));
		$collection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('type', 'product'));

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::count(2, $array['bool']['must']);
	}


	public function testWithShouldQueries(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addShouldQuery(new \Spameri\ElasticQuery\Query\Term('category', 'electronics'));
		$collection->addShouldQuery(new \Spameri\ElasticQuery\Query\Term('category', 'books'));

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['bool']['should']));
		\Tester\Assert::count(2, $array['bool']['should']);
	}


	public function testWithMustNotQueries(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addMustNotQuery(new \Spameri\ElasticQuery\Query\Term('status', 'deleted'));

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['bool']['must_not']));
		\Tester\Assert::count(1, $array['bool']['must_not']);
	}


	public function testComposeMustShouldMustNot(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('status', 'active'));
		$collection->addShouldQuery(new \Spameri\ElasticQuery\Query\Term('featured', true));
		$collection->addMustNotQuery(new \Spameri\ElasticQuery\Query\Term('status', 'deleted'));

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::true(isset($array['bool']['should']));
		\Tester\Assert::true(isset($array['bool']['must_not']));
	}


	public function testConstructorWithCollections(): void
	{
		$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection(
			new \Spameri\ElasticQuery\Query\Term('status', 'active'),
		);
		$shouldCollection = new \Spameri\ElasticQuery\Query\ShouldCollection(
			new \Spameri\ElasticQuery\Query\Term('featured', true),
		);
		$mustNotCollection = new \Spameri\ElasticQuery\Query\MustNotCollection(
			new \Spameri\ElasticQuery\Query\Term('status', 'deleted'),
		);

		$collection = new \Spameri\ElasticQuery\Query\QueryCollection(
			null,
			$mustCollection,
			$shouldCollection,
			$mustNotCollection,
		);

		$array = $collection->toArray();

		\Tester\Assert::count(1, $array['bool']['must']);
		\Tester\Assert::count(1, $array['bool']['should']);
		\Tester\Assert::count(1, $array['bool']['must_not']);
	}


	public function testMust(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();

		$must = $collection->must();

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\MustCollection::class, $must);
	}


	public function testShould(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();

		$should = $collection->should();

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\ShouldCollection::class, $should);
	}


	public function testMustNot(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();

		$mustNot = $collection->mustNot();

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\MustNotCollection::class, $mustNot);
	}


	public function testKeyWithCustomKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection('custom_key');

		\Tester\Assert::same('custom_key', $collection->key());
	}


	public function testKeyWithIntKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection(123);

		\Tester\Assert::same('123', $collection->key());
	}


	public function testKeyGeneratedFromContent(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$key = $collection->key();

		\Tester\Assert::type('string', $key);
		\Tester\Assert::true(\strlen($key) === 32);
	}


	public function testToArrayStructure(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$collection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['bool']));
		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::true(isset($array['bool']['must'][0]['term']));
	}


	public function testDirectlyAddToSubCollections(): void
	{
		$collection = new \Spameri\ElasticQuery\Query\QueryCollection();

		$collection->must()->add(new \Spameri\ElasticQuery\Query\Term('field1', 'value1'));
		$collection->should()->add(new \Spameri\ElasticQuery\Query\Term('field2', 'value2'));
		$collection->mustNot()->add(new \Spameri\ElasticQuery\Query\Term('field3', 'value3'));

		$array = $collection->toArray();

		\Tester\Assert::count(1, $array['bool']['must']);
		\Tester\Assert::count(1, $array['bool']['should']);
		\Tester\Assert::count(1, $array['bool']['must_not']);
	}


	public function testNestedQueryCollection(): void
	{
		$innerCollection = new \Spameri\ElasticQuery\Query\QueryCollection('inner');
		$innerCollection->addMustQuery(new \Spameri\ElasticQuery\Query\Term('inner_field', 'inner_value'));

		$outerCollection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$outerCollection->addMustQuery($innerCollection);

		$array = $outerCollection->toArray();

		\Tester\Assert::true(isset($array['bool']['must'][0]['bool']));
	}

}

(new QueryCollection())->run();
