<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Mapping;

require_once __DIR__ . '/../../../../bootstrap.php';


class SubFields extends \Tester\TestCase
{

	public function testKey(): void
	{
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields('title');

		\Tester\Assert::same('title', $subFields->key());
	}


	public function testToArrayDefaultType(): void
	{
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields('title');

		$array = $subFields->toArray();

		\Tester\Assert::same('keyword', $array['type']);
		\Tester\Assert::true(isset($array['fields']));
		\Tester\Assert::same([], $array['fields']);
	}


	public function testToArrayWithTextType(): void
	{
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$array = $subFields->toArray();

		\Tester\Assert::same('text', $array['type']);
	}


	public function testToArrayWithSubField(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$array = $subFields->toArray();

		\Tester\Assert::same('text', $array['type']);
		\Tester\Assert::true(isset($array['fields']['raw']));
		\Tester\Assert::same('keyword', $array['fields']['raw']['type']);
	}


	public function testToArrayWithMultipleSubFields(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('english', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('autocomplete', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$array = $subFields->toArray();

		\Tester\Assert::same('text', $array['type']);
		\Tester\Assert::count(3, $array['fields']);
		\Tester\Assert::same('keyword', $array['fields']['raw']['type']);
		\Tester\Assert::same('text', $array['fields']['english']['type']);
		\Tester\Assert::same('text', $array['fields']['autocomplete']['type']);
	}


	public function testGetFields(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$fields = $subFields->getFields();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection::class, $fields);
		\Tester\Assert::same(1, $fields->count());
		\Tester\Assert::true($fields->isValue('raw'));
	}


	public function testAddMappingField(): void
	{
		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$subFields->addMappingField(new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'raw',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD,
		));

		$array = $subFields->toArray();

		\Tester\Assert::true(isset($array['fields']['raw']));
		\Tester\Assert::same('keyword', $array['fields']['raw']['type']);
	}


	public function testRemoveMappingField(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('raw', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('english', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'title',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$subFields->removeMappingField('raw');

		$array = $subFields->toArray();

		\Tester\Assert::false(isset($array['fields']['raw']));
		\Tester\Assert::true(isset($array['fields']['english']));
	}


	public function testInvalidTypeThrowsException(): void
	{
		\Tester\Assert::exception(function (): void {
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
				'test',
				'invalid_type',
			);
		}, \Spameri\ElasticQuery\Exception\InvalidArgumentException::class);
	}


	public function testTypicalUseCaseForTextWithKeywordSubField(): void
	{
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('keyword', \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'name',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$array = $subFields->toArray();

		// Main field is text for full-text search
		\Tester\Assert::same('text', $array['type']);

		// Sub-field is keyword for sorting/aggregations
		\Tester\Assert::same('keyword', $array['fields']['keyword']['type']);
	}


	public function testSubFieldWithAnalyzer(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();
		$fieldCollection = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'analyzed',
				\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
				$analyzer,
			),
		);

		$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
			'content',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$fieldCollection,
		);

		$array = $subFields->toArray();

		\Tester\Assert::same('text', $array['type']);
		\Tester\Assert::same('text', $array['fields']['analyzed']['type']);
		\Tester\Assert::same('englishDictionary', $array['fields']['analyzed']['analyzer']);
	}

}

(new SubFields())->run();
