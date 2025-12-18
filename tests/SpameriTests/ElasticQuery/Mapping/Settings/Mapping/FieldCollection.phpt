<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Mapping;

require_once __DIR__ . '/../../../../bootstrap.php';


class FieldCollection extends \Tester\TestCase
{

	public function testEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testConstructorWithFields(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('description', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		\Tester\Assert::same(2, $collection->count());
		\Tester\Assert::contains('name', $collection->keys());
		\Tester\Assert::contains('description', $collection->keys());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('title', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT));

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue('title'));
	}


	public function testAddReplacesSameKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT));
		$collection->add(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD));

		\Tester\Assert::same(1, $collection->count());

		$field = $collection->get('name');
		\Tester\Assert::same('keyword', $field->toArray()['name']['type']);
	}


	public function testRemove(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$result = $collection->remove('name');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::false($collection->isValue('name'));
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testGet(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$field = $collection->get('name');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Mapping\Field::class, $field);
		\Tester\Assert::same('name', $field->key());
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();

		$field = $collection->get('non_existent');

		\Tester\Assert::null($field);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		\Tester\Assert::true($collection->isValue('name'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testCount(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field1', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field2', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field3', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER),
		);

		\Tester\Assert::same(3, $collection->count());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('alpha', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('beta', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('alpha', $keys);
		\Tester\Assert::contains('beta', $keys);
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('description', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field1', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field2', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(2, $iterator->count());
	}


	public function testForeach(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field1', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('field2', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$keys = [];
		foreach ($collection as $key => $field) {
			$keys[] = $key;
			\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface::class, $field);
		}

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('field1', $keys);
		\Tester\Assert::contains('field2', $keys);
	}


	public function testMixedFieldTypes(): void
	{
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$nestedFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$objectFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('simple', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields('title', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT, $subFieldCollection),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $nestedFields),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $objectFields),
		);

		\Tester\Assert::same(4, $collection->count());
		\Tester\Assert::true($collection->isValue('simple'));
		\Tester\Assert::true($collection->isValue('title'));
		\Tester\Assert::true($collection->isValue('tags'));
		\Tester\Assert::true($collection->isValue('author'));
	}

}

(new FieldCollection())->run();
