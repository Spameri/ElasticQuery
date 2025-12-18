<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Collection;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Test entity implementation for testing AbstractCollection
 */
class TestEntity extends \Spameri\ElasticQuery\Entity\AbstractEntity
{

	public function __construct(
		private string $key,
		private string $value,
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	public function toArray(): array
	{
		return [
			'key' => $this->key,
			'value' => $this->value,
		];
	}


	public function getValue(): string
	{
		return $this->value;
	}

}


/**
 * Concrete collection implementation for testing AbstractCollection
 */
class TestCollection extends \Spameri\ElasticQuery\Collection\AbstractCollection
{

}


class AbstractCollection extends \Tester\TestCase
{

	public function testEmptyCollection(): void
	{
		$collection = new TestCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testAddItem(): void
	{
		$collection = new TestCollection();
		$entity = new TestEntity('key1', 'value1');

		$collection->add($entity);

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue('key1'));
	}


	public function testConstructorWithItems(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key2', 'value2');

		$collection = new TestCollection($entity1, $entity2);

		\Tester\Assert::same(2, $collection->count());
		\Tester\Assert::true($collection->isValue('key1'));
		\Tester\Assert::true($collection->isValue('key2'));
	}


	public function testGetItem(): void
	{
		$entity = new TestEntity('key1', 'value1');
		$collection = new TestCollection($entity);

		$retrieved = $collection->get('key1');

		\Tester\Assert::same($entity, $retrieved);
	}


	public function testGetNonExistentReturnsNull(): void
	{
		$collection = new TestCollection();

		$result = $collection->get('non_existent_key');

		\Tester\Assert::null($result);
	}


	public function testRemoveItem(): void
	{
		$entity = new TestEntity('key1', 'value1');
		$collection = new TestCollection($entity);

		$removed = $collection->remove('key1');

		\Tester\Assert::true($removed);
		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::false($collection->isValue('key1'));
	}


	public function testRemoveNonExistentReturnsFalse(): void
	{
		$collection = new TestCollection();

		$removed = $collection->remove('non_existent_key');

		\Tester\Assert::false($removed);
	}


	public function testIsValue(): void
	{
		$entity = new TestEntity('key1', 'value1');
		$collection = new TestCollection($entity);

		\Tester\Assert::true($collection->isValue('key1'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testKeys(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key2', 'value2');
		$collection = new TestCollection($entity1, $entity2);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('key1', $keys);
		\Tester\Assert::contains('key2', $keys);
	}


	public function testClear(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key2', 'value2');
		$collection = new TestCollection($entity1, $entity2);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testGetIterator(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key2', 'value2');
		$collection = new TestCollection($entity1, $entity2);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(2, $iterator->count());
	}


	public function testIterateWithForeach(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key2', 'value2');
		$collection = new TestCollection($entity1, $entity2);

		$count = 0;
		foreach ($collection as $key => $item) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Entity\EntityInterface::class, $item);
			\Tester\Assert::type('string', $key);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testKeyUniqueness(): void
	{
		$entity1 = new TestEntity('key1', 'value1');
		$entity2 = new TestEntity('key1', 'value2');
		$collection = new TestCollection();

		$collection->add($entity1);
		$collection->add($entity2);

		\Tester\Assert::same(1, $collection->count());
		$retrieved = $collection->get('key1');
		\Tester\Assert::type(TestEntity::class, $retrieved);
		\Tester\Assert::same('value2', $retrieved->getValue());
	}


	public function testNumericKeys(): void
	{
		$entity1 = new TestEntity('123', 'value1');
		$entity2 = new TestEntity('456', 'value2');
		$collection = new TestCollection($entity1, $entity2);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::type('string', $keys[0]);
		\Tester\Assert::type('string', $keys[1]);
	}

}

(new AbstractCollection())->run();
