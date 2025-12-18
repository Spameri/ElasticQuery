<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Value;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Concrete implementation for testing AbstractFloatValue
 */
class TestFloatValue extends \Spameri\ElasticQuery\Value\AbstractFloatValue
{

}


class AbstractFloatValue extends \Tester\TestCase
{

	public function testValue(): void
	{
		$value = new TestFloatValue(3.14);

		\Tester\Assert::same(3.14, $value->value());
	}


	public function testZeroValue(): void
	{
		$value = new TestFloatValue(0.0);

		\Tester\Assert::same(0.0, $value->value());
	}


	public function testNegativeValue(): void
	{
		$value = new TestFloatValue(-123.456);

		\Tester\Assert::same(-123.456, $value->value());
	}


	public function testIntegerAsFloat(): void
	{
		$value = new TestFloatValue(42.0);

		\Tester\Assert::same(42.0, $value->value());
		\Tester\Assert::type('float', $value->value());
	}


	public function testSmallDecimal(): void
	{
		$value = new TestFloatValue(0.0000001);

		\Tester\Assert::same(0.0000001, $value->value());
	}


	public function testLargeValue(): void
	{
		$value = new TestFloatValue(1.7976931348623157E+308);

		\Tester\Assert::same(1.7976931348623157E+308, $value->value());
	}


	public function testImplementsInterface(): void
	{
		$value = new TestFloatValue(1.0);

		\Tester\Assert::type(\Spameri\ElasticQuery\Value\ValueInterface::class, $value);
	}


	public function testReturnType(): void
	{
		$value = new TestFloatValue(3.14);

		\Tester\Assert::type('float', $value->value());
	}


	public function testScientificNotation(): void
	{
		$value = new TestFloatValue(1.5e10);

		\Tester\Assert::same(1.5e10, $value->value());
	}

}

(new AbstractFloatValue())->run();
