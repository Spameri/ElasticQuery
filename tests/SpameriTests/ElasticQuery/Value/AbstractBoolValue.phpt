<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Value;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Concrete implementation for testing AbstractBoolValue
 */
class TestBoolValue extends \Spameri\ElasticQuery\Value\AbstractBoolValue
{

}


class AbstractBoolValue extends \Tester\TestCase
{

	public function testTrueValue(): void
	{
		$value = new TestBoolValue(true);

		\Tester\Assert::true($value->value());
	}


	public function testFalseValue(): void
	{
		$value = new TestBoolValue(false);

		\Tester\Assert::false($value->value());
	}


	public function testImplementsInterface(): void
	{
		$value = new TestBoolValue(true);

		\Tester\Assert::type(\Spameri\ElasticQuery\Value\ValueInterface::class, $value);
	}


	public function testReturnType(): void
	{
		$value = new TestBoolValue(true);

		\Tester\Assert::type('bool', $value->value());
	}


	public function testTrueIsNotOne(): void
	{
		$value = new TestBoolValue(true);

		\Tester\Assert::notSame(1, $value->value());
	}


	public function testFalseIsNotZero(): void
	{
		$value = new TestBoolValue(false);

		\Tester\Assert::notSame(0, $value->value());
	}

}

(new AbstractBoolValue())->run();
