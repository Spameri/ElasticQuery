<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Mapping;

require_once __DIR__ . '/../../../../bootstrap.php';


class FieldObject extends \Tester\TestCase
{

	public function testKey(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		\Tester\Assert::same('author', $fieldObject->key());
	}


	public function testToArrayEmpty(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		$array = $fieldObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::true(isset($array['properties']));
		\Tester\Assert::same([], $array['properties']);
	}


	public function testToArrayWithSimpleFields(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('first_name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('last_name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('email', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		$array = $fieldObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::true(isset($array['properties']['first_name']));
		\Tester\Assert::true(isset($array['properties']['last_name']));
		\Tester\Assert::true(isset($array['properties']['email']));
		\Tester\Assert::same('keyword', $array['properties']['first_name']['type']);
		\Tester\Assert::same('keyword', $array['properties']['last_name']['type']);
		\Tester\Assert::same('keyword', $array['properties']['email']['type']);
	}


	public function testToArrayWithNestedFieldObject(): void
	{
		$addressFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('street', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('city', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('zip', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$addressObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('address', $addressFields);

		$personFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$personFields->add($addressObject);

		$personObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('person', $personFields);

		$array = $personObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::true(isset($array['properties']['name']));
		\Tester\Assert::true(isset($array['properties']['address']));
		\Tester\Assert::same('object', $array['properties']['address']['type']);
		\Tester\Assert::true(isset($array['properties']['address']['properties']['street']));
		\Tester\Assert::true(isset($array['properties']['address']['properties']['city']));
		\Tester\Assert::true(isset($array['properties']['address']['properties']['zip']));
	}


	public function testToArrayWithNestedObject(): void
	{
		$tagFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('value', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $tagFields);

		$productFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$productFields->add($nestedObject);

		$productObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('product', $productFields);

		$array = $productObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::true(isset($array['properties']['tags']));
		\Tester\Assert::same('nested', $array['properties']['tags']['type']);
	}


	public function testToArrayWithSubFields(): void
	{
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$subFieldCollection,
		);

		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fields->add($subFields);

		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('document', $fields);

		$array = $fieldObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::true(isset($array['properties']['title']));
		\Tester\Assert::same('text', $array['properties']['title']['type']);
		\Tester\Assert::true(isset($array['properties']['title']['fields']['raw']));
	}


	public function testToArrayWithMixedFieldTypes(): void
	{
		$addressFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('city', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$tagFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('value', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('simple', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('address', $addressFields),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $tagFields),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields('title', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT, $subFieldCollection),
		);

		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('complex', $fields);

		$array = $fieldObject->toArray();

		\Tester\Assert::same('object', $array['type']);
		\Tester\Assert::same('keyword', $array['properties']['simple']['type']);
		\Tester\Assert::same('object', $array['properties']['address']['type']);
		\Tester\Assert::same('nested', $array['properties']['tags']['type']);
		\Tester\Assert::same('text', $array['properties']['title']['type']);
	}

}

(new FieldObject())->run();
