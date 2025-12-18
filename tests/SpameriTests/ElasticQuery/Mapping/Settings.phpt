<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping;

require_once __DIR__ . '/../../bootstrap.php';


class Settings extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']));
		\Tester\Assert::true(isset($array['settings']['analysis']));
		\Tester\Assert::true(isset($array['mappings']));
		\Tester\Assert::true(isset($array['mappings']['properties']));
		\Tester\Assert::same(true, $array['mappings']['dynamic']);
	}


	public function testIndexName(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('my_index');

		\Tester\Assert::same('my_index', $settings->indexName());
	}


	public function testChangeIndexName(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('old_index');

		$settings->changeIndexName('new_index');

		\Tester\Assert::same('new_index', $settings->indexName());
	}


	public function testHasSti(): void
	{
		$settingsWithoutSti = new \Spameri\ElasticQuery\Mapping\Settings('index', false);
		$settingsWithSti = new \Spameri\ElasticQuery\Mapping\Settings('index', true);

		\Tester\Assert::false($settingsWithoutSti->hasSti());
		\Tester\Assert::true($settingsWithSti->hasSti());
	}


	public function testAnalysis(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis::class, $settings->analysis());
	}


	public function testMapping(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Mapping::class, $settings->mapping());
	}


	public function testAddMappingField(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$settings->addMappingField($field);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['title']));
		\Tester\Assert::same('text', $array['mappings']['properties']['title']['type']);
	}


	public function testAddMappingFieldKeyword(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		$settings->addMappingFieldKeyword('status');

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['status']));
		\Tester\Assert::same('keyword', $array['mappings']['properties']['status']['type']);
	}


	public function testAddMappingFieldFloat(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		$settings->addMappingFieldFloat('price');

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['price']));
		\Tester\Assert::same('float', $array['mappings']['properties']['price']['type']);
	}


	public function testAddMappingFieldInteger(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		$settings->addMappingFieldInteger('count');

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['count']));
		\Tester\Assert::same('integer', $array['mappings']['properties']['count']['type']);
	}


	public function testAddMappingFieldBoolean(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');

		$settings->addMappingFieldBoolean('active');

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['active']));
		\Tester\Assert::same('boolean', $array['mappings']['properties']['active']['type']);
	}


	public function testAddMappingFieldObject(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('author', $fields);

		$settings->addMappingFieldObject($fieldObject);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['author']));
		\Tester\Assert::same('object', $array['mappings']['properties']['author']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['author']['properties']['name']));
	}


	public function testAddMappingNestedObject(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('tag', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$nestedObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('tags', $fields);

		$settings->addMappingNestedObject($nestedObject);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['tags']));
		\Tester\Assert::same('nested', $array['mappings']['properties']['tags']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['tags']['properties']['tag']));
	}


	public function testAddMappingSubField(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$subFieldCollection,
		);

		$settings->addMappingSubField($subFields);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['mappings']['properties']['title']));
		\Tester\Assert::same('text', $array['mappings']['properties']['title']['type']);
		\Tester\Assert::true(isset($array['mappings']['properties']['title']['fields']['raw']));
	}


	public function testRemoveMappingSubField(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$subFieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$subFieldCollection,
		);

		$settings->addMappingSubField($subFields);
		$settings->removeMappingSubField('title');

		$array = $settings->toArray();

		\Tester\Assert::false(isset($array['mappings']['properties']['title']));
	}


	public function testAddAnalyzer(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$settings->addAnalyzer($analyzer);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']['analysis']['analyzer']['englishDictionary']));
		\Tester\Assert::same('custom', $array['settings']['analysis']['analyzer']['englishDictionary']['type']);
	}


	public function testRemoveAnalyzer(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('test_index');
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$settings->addAnalyzer($analyzer);
		$settings->removeAnalyzer('englishDictionary');

		$array = $settings->toArray();

		\Tester\Assert::false(isset($array['settings']['analysis']['analyzer']['englishDictionary']));
	}


	public function testToArrayWithAliases(): void
	{
		$aliasCollection = new \Spameri\ElasticQuery\Mapping\Settings\AliasCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Alias('alias_one'),
			new \Spameri\ElasticQuery\Mapping\Settings\Alias('alias_two'),
		);

		$settings = new \Spameri\ElasticQuery\Mapping\Settings(
			'test_index',
			false,
			null,
			null,
			$aliasCollection,
		);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['aliases']));
		\Tester\Assert::true(isset($array['aliases']['alias_one']));
		\Tester\Assert::true(isset($array['aliases']['alias_two']));
	}


	public function testComplexMapping(): void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings('products');

		// Add various field types
		$settings->addMappingFieldKeyword('sku');
		$settings->addMappingFieldFloat('price');
		$settings->addMappingFieldInteger('quantity');
		$settings->addMappingFieldBoolean('in_stock');

		// Add text field
		$settings->addMappingField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'description',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		));

		// Add nested object for categories
		$categoryFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('id', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);
		$settings->addMappingNestedObject(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('categories', $categoryFields));

		$array = $settings->toArray();

		\Tester\Assert::same('keyword', $array['mappings']['properties']['sku']['type']);
		\Tester\Assert::same('float', $array['mappings']['properties']['price']['type']);
		\Tester\Assert::same('integer', $array['mappings']['properties']['quantity']['type']);
		\Tester\Assert::same('boolean', $array['mappings']['properties']['in_stock']['type']);
		\Tester\Assert::same('text', $array['mappings']['properties']['description']['type']);
		\Tester\Assert::same('nested', $array['mappings']['properties']['categories']['type']);
	}

}

(new Settings())->run();
