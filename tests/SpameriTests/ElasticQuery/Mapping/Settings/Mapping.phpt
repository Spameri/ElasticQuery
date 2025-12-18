<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings;

require_once __DIR__ . '/../../../bootstrap.php';


class Mapping extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');

		$array = $mapping->toArray();

		\Tester\Assert::true(isset($array['mappings']));
		\Tester\Assert::true(isset($array['mappings']['properties']));
		\Tester\Assert::same([], $array['mappings']['properties']);
		\Tester\Assert::same(true, $array['mappings']['dynamic']);
	}


	public function testGetIndexName(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('my_index');

		\Tester\Assert::same('my_index', $mapping->getIndexName());
	}


	public function testEnableStrictMapping(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');

		$mapping->enableStrictMapping();

		$array = $mapping->toArray();

		\Tester\Assert::same(false, $array['mappings']['dynamic']);
	}


	public function testDynamicFalseViaConstructor(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping(
			'test_index',
			null,
			false,
		);

		$array = $mapping->toArray();

		\Tester\Assert::same(false, $array['mappings']['dynamic']);
	}


	public function testFields(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index', $fieldCollection);

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection::class, $mapping->fields());
		\Tester\Assert::same(1, $mapping->fields()->count());
	}


	public function testAddField(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$mapping->addField($field);

		$array = $mapping->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['title']));
		\Tester\Assert::same('text', $array['mappings']['properties']['title']['type']);
	}


	public function testAddFieldObject(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('first_name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('last_name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		$mapping->addFieldObject($fieldObject);

		$array = $mapping->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['author']));
		\Tester\Assert::same('object', $array['mappings']['properties']['author']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['author']['properties']));
		\Tester\Assert::true(isset($array['mappings']['properties']['author']['properties']['first_name']));
		\Tester\Assert::true(isset($array['mappings']['properties']['author']['properties']['last_name']));
	}


	public function testAddNestedObject(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('categories', $fields);

		$mapping->addNestedObject($nestedObject);

		$array = $mapping->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['categories']));
		\Tester\Assert::same('nested', $array['mappings']['properties']['categories']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['categories']['properties']));
		\Tester\Assert::true(isset($array['mappings']['properties']['categories']['properties']['id']));
		\Tester\Assert::true(isset($array['mappings']['properties']['categories']['properties']['name']));
	}


	public function testRemoveFieldObject(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		$mapping->addFieldObject($fieldObject);
		$mapping->removeFieldObject('author');

		$array = $mapping->toArray();

		\Tester\Assert::false(isset($array['mappings']['properties']['author']));
	}


	public function testAddSubField(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$subFieldCollection,
		);

		$mapping->addSubField($subFields);

		$array = $mapping->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['title']));
		\Tester\Assert::same('text', $array['mappings']['properties']['title']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['title']['fields']['raw']));
		\Tester\Assert::same('keyword', $array['mappings']['properties']['title']['fields']['raw']['type']);
	}


	public function testRemoveSubField(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('test_index');
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$subFieldCollection,
		);

		$mapping->addSubField($subFields);
		$mapping->removeSubField('title');

		$array = $mapping->toArray();

		\Tester\Assert::false(isset($array['mappings']['properties']['title']));
	}


	public function testComplexMapping(): void
	{
		$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping('products');

		// Add simple fields
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD));
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT));
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('price', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_FLOAT));
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('stock', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER));
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('active', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_BOOLEAN));
		$mapping->addField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('created_at', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DATE));

		// Add object field
		$brandFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$mapping->addFieldObject(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('brand', $brandFields));

		// Add nested field
		$tagFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('value', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$mapping->addNestedObject(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $tagFields));

		$array = $mapping->toArray();

		\Tester\Assert::same('keyword', $array['mappings']['properties']['id']['type']);
		\Tester\Assert::same('text', $array['mappings']['properties']['name']['type']);
		\Tester\Assert::same('float', $array['mappings']['properties']['price']['type']);
		\Tester\Assert::same('integer', $array['mappings']['properties']['stock']['type']);
		\Tester\Assert::same('boolean', $array['mappings']['properties']['active']['type']);
		\Tester\Assert::same('date', $array['mappings']['properties']['created_at']['type']);
		\Tester\Assert::same('object', $array['mappings']['properties']['brand']['type']);
		\Tester\Assert::same('nested', $array['mappings']['properties']['tags']['type']);
	}

}

(new Mapping())->run();
