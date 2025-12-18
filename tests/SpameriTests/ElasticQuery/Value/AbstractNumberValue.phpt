<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Value;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Concrete implementation for testing AbstractNumberValue
 */
class TestNumberValue extends \Spameri\ElasticQuery\Value\AbstractNumberValue
{

}


class AbstractNumberValue extends \Tester\TestCase
{

	public function testValue(): void
	{
		$value = new TestNumberValue(42);

		\Tester\Assert::same(42, $value->value());
	}


	public function testZeroValue(): void
	{
		$value = new TestNumberValue(0);

		\Tester\Assert::same(0, $value->value());
	}


	public function testNegativeValue(): void
	{
		$value = new TestNumberValue(-123);

		\Tester\Assert::same(-123, $value->value());
	}


	public function testLargePositiveValue(): void
	{
		$value = new TestNumberValue(\PHP_INT_MAX);

		\Tester\Assert::same(\PHP_INT_MAX, $value->value());
	}


	public function testLargeNegativeValue(): void
	{
		$value = new TestNumberValue(\PHP_INT_MIN);

		\Tester\Assert::same(\PHP_INT_MIN, $value->value());
	}


	public function testImplementsInterface(): void
	{
		$value = new TestNumberValue(1);

		\Tester\Assert::type(\Spameri\ElasticQuery\Value\ValueInterface::class, $value);
	}


	public function testReturnType(): void
	{
		$value = new TestNumberValue(42);

		\Tester\Assert::type('int', $value->value());
	}

}

(new AbstractNumberValue())->run();
