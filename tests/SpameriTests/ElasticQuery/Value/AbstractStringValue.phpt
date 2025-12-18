<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Value;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Concrete implementation for testing AbstractStringValue
 */
class TestStringValue extends \Spameri\ElasticQuery\Value\AbstractStringValue
{

}


class AbstractStringValue extends \Tester\TestCase
{

	public function testValue(): void
	{
		$value = new TestStringValue('test_string');

		\Tester\Assert::same('test_string', $value->value());
	}


	public function testEmptyValue(): void
	{
		$value = new TestStringValue('');

		\Tester\Assert::same('', $value->value());
	}


	public function testNumericString(): void
	{
		$value = new TestStringValue('12345');

		\Tester\Assert::same('12345', $value->value());
		\Tester\Assert::type('string', $value->value());
	}


	public function testUnicodeValue(): void
	{
		$value = new TestStringValue('česká hodnota');

		\Tester\Assert::same('česká hodnota', $value->value());
	}


	public function testSpecialCharacters(): void
	{
		$value = new TestStringValue('value with "quotes" and \'apostrophes\'');

		\Tester\Assert::same('value with "quotes" and \'apostrophes\'', $value->value());
	}


	public function testNewlinesAndTabs(): void
	{
		$value = new TestStringValue("line1\nline2\ttabbed");

		\Tester\Assert::same("line1\nline2\ttabbed", $value->value());
	}


	public function testImplementsInterface(): void
	{
		$value = new TestStringValue('test');

		\Tester\Assert::type(\Spameri\ElasticQuery\Value\ValueInterface::class, $value);
	}


	public function testLongString(): void
	{
		$longString = \str_repeat('a', 10000);
		$value = new TestStringValue($longString);

		\Tester\Assert::same($longString, $value->value());
		\Tester\Assert::same(10000, \strlen($value->value()));
	}

}

(new AbstractStringValue())->run();
