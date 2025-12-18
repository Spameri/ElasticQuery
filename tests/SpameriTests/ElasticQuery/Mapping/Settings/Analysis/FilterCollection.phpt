<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Analysis;

require_once __DIR__ . '/../../../../bootstrap.php';


class FilterCollection extends \Tester\TestCase
{

	public function testEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testConstructorWithFilters(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
		);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Filter\Lowercase());

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue('lowercase'));
	}


	public function testAddReplacesSameKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Filter\Stemmer());
		$collection->add(new \Spameri\ElasticQuery\Mapping\Filter\Stemmer());

		\Tester\Assert::same(1, $collection->count());
	}


	public function testRemove(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
		);

		$result = $collection->remove('lowercase');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::false($collection->isValue('lowercase'));
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testGet(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
		);

		$filter = $collection->get('stemmer');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
		\Tester\Assert::same('stemmer', $filter->key());
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$filter = $collection->get('non_existent');

		\Tester\Assert::null($filter);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
		);

		\Tester\Assert::true($collection->isValue('lowercase'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testCount(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
			new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding(),
		);

		\Tester\Assert::same(3, $collection->count());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
		);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('lowercase', $keys);
		\Tester\Assert::contains('stemmer', $keys);
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
		);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
		);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(2, $iterator->count());
	}


	public function testForeach(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection(
			new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			new \Spameri\ElasticQuery\Mapping\Filter\Stemmer(),
		);

		$keys = [];
		foreach ($collection as $key => $filter) {
			$keys[] = $key;
			\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
		}

		\Tester\Assert::count(2, $keys);
	}

}

(new FilterCollection())->run();
