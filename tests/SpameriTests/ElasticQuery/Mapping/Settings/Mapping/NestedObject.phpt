<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Mapping;

require_once __DIR__ . '/../../../../bootstrap.php';


class NestedObject extends \Tester\TestCase
{

	public function testKey(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $fields);

		\Tester\Assert::same('tags', $nestedObject->key());
	}


	public function testToArrayEmpty(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $fields);

		$array = $nestedObject->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::true(isset($array['properties']));
		\Tester\Assert::same([], $array['properties']);
	}


	public function testToArrayWithSimpleFields(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('count', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER),
		);
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $fields);

		$array = $nestedObject->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::true(isset($array['properties']['id']));
		\Tester\Assert::true(isset($array['properties']['name']));
		\Tester\Assert::true(isset($array['properties']['count']));
		\Tester\Assert::same('keyword', $array['properties']['id']['type']);
		\Tester\Assert::same('keyword', $array['properties']['name']['type']);
		\Tester\Assert::same('integer', $array['properties']['count']['type']);
	}


	public function testToArrayWithNestedNestedObject(): void
	{
		$innerFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('value', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$innerNested = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('attributes', $innerFields);

		$outerFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$outerFields->add($innerNested);

		$outerNested = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('categories', $outerFields);

		$array = $outerNested->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::true(isset($array['properties']['name']));
		\Tester\Assert::true(isset($array['properties']['attributes']));
		\Tester\Assert::same('nested', $array['properties']['attributes']['type']);
		\Tester\Assert::true(isset($array['properties']['attributes']['properties']['value']));
	}


	public function testToArrayWithFieldObject(): void
	{
		$addressFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('street', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('city', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$addressObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('address', $addressFields);

		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fields->add($addressObject);

		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('locations', $fields);

		$array = $nestedObject->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::true(isset($array['properties']['address']));
		\Tester\Assert::same('object', $array['properties']['address']['type']);
		\Tester\Assert::true(isset($array['properties']['address']['properties']['street']));
		\Tester\Assert::true(isset($array['properties']['address']['properties']['city']));
	}


	public function testToArrayWithMixedFieldTypes(): void
	{
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$metaFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('key', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('simple', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('meta', $metaFields),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('inner_nested', $metaFields),
		);

		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('items', $fields);

		$array = $nestedObject->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::same('keyword', $array['properties']['simple']['type']);
		\Tester\Assert::same('object', $array['properties']['meta']['type']);
		\Tester\Assert::same('nested', $array['properties']['inner_nested']['type']);
	}


	public function testTypicalUseCaseForComments(): void
	{
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('author', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('text', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('created_at', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DATE),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('likes', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER),
		);

		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('comments', $fields);

		$array = $nestedObject->toArray();

		\Tester\Assert::same('nested', $array['type']);
		\Tester\Assert::same('keyword', $array['properties']['id']['type']);
		\Tester\Assert::same('keyword', $array['properties']['author']['type']);
		\Tester\Assert::same('text', $array['properties']['text']['type']);
		\Tester\Assert::same('date', $array['properties']['created_at']['type']);
		\Tester\Assert::same('integer', $array['properties']['likes']['type']);
	}

}

(new NestedObject())->run();
