<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Mapping;

require_once __DIR__ . '/../../../../bootstrap.php';


class Field extends \Tester\TestCase
{

	public function testToArrayDefault(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('name');

		$array = $field->toArray();

		\Tester\Assert::true(isset($array['name']));
		\Tester\Assert::same('keyword', $array['name']['type']);
	}


	public function testKey(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('my_field');

		\Tester\Assert::same('my_field', $field->key());
	}


	public function testToArrayWithText(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'description',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$array = $field->toArray();

		\Tester\Assert::same('text', $array['description']['type']);
	}


	public function testToArrayWithInteger(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'count',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER,
		);

		$array = $field->toArray();

		\Tester\Assert::same('integer', $array['count']['type']);
	}


	public function testToArrayWithFloat(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'price',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_FLOAT,
		);

		$array = $field->toArray();

		\Tester\Assert::same('float', $array['price']['type']);
	}


	public function testToArrayWithBoolean(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'active',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_BOOLEAN,
		);

		$array = $field->toArray();

		\Tester\Assert::same('boolean', $array['active']['type']);
	}


	public function testToArrayWithDate(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'created_at',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DATE,
		);

		$array = $field->toArray();

		\Tester\Assert::same('date', $array['created_at']['type']);
	}


	public function testToArrayWithGeoPoint(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'location',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_GEO_POINT,
		);

		$array = $field->toArray();

		\Tester\Assert::same('geo_point', $array['location']['type']);
	}


	public function testToArrayWithAnalyzer(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'description',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			$analyzer,
		);

		$array = $field->toArray();

		\Tester\Assert::same('text', $array['description']['type']);
		\Tester\Assert::same('englishDictionary', $array['description']['analyzer']);
	}


	public function testChangeAnalyzer(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'description',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$array = $field->toArray();
		\Tester\Assert::false(isset($array['description']['analyzer']));

		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();
		$field->changeAnalyzer($analyzer);

		$array = $field->toArray();
		\Tester\Assert::same('englishDictionary', $array['description']['analyzer']);
	}


	public function testToArrayWithFieldDataTrue(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'tags',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			null,
			true,
		);

		$array = $field->toArray();

		\Tester\Assert::same('text', $array['tags']['type']);
		\Tester\Assert::same(true, $array['tags']['fielddata']);
	}


	public function testToArrayWithFieldDataFalse(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'tags',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
			null,
			false,
		);

		$array = $field->toArray();

		\Tester\Assert::same('text', $array['tags']['type']);
		\Tester\Assert::same(false, $array['tags']['fielddata']);
	}


	public function testToArrayWithoutFieldData(): void
	{
		$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
			'tags',
			\Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		);

		$array = $field->toArray();

		\Tester\Assert::false(isset($array['tags']['fielddata']));
	}


	public function testInvalidTypeThrowsException(): void
	{
		\Tester\Assert::exception(function (): void {
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				'test',
				'invalid_type',
			);
		}, \Spameri\ElasticQuery\Exception\InvalidArgumentException::class);
	}


	public function testAllNumericTypes(): void
	{
		$types = [
			'integer' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER,
			'long' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_LONG,
			'short' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_SHORT,
			'byte' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_BYTE,
			'double' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DOUBLE,
			'float' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_FLOAT,
			'half_float' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_HALF_FLOAT,
			'scaled_float' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_SCALED_FLOAT,
		];

		foreach ($types as $expected => $type) {
			$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('num_field', $type);
			$array = $field->toArray();
			\Tester\Assert::same($expected, $array['num_field']['type']);
		}
	}


	public function testRangeTypes(): void
	{
		$types = [
			'integer_range' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER_RANGE,
			'float_range' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_FLOAT_RANGE,
			'long_range' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_LONG_RANGE,
			'double_range' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DOUBLE_RANGE,
			'date_range' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_DATE_RANGE,
		];

		foreach ($types as $expected => $type) {
			$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('range_field', $type);
			$array = $field->toArray();
			\Tester\Assert::same($expected, $array['range_field']['type']);
		}
	}


	public function testSpecialTypes(): void
	{
		$types = [
			'ip' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_IP,
			'completion' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_COMPLETION,
			'token_count' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TOKEN_COUNT,
		];

		foreach ($types as $expected => $type) {
			$field = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field('special_field', $type);
			$array = $field->toArray();
			\Tester\Assert::same($expected, $array['special_field']['type']);
		}
	}

}

(new Field())->run();
