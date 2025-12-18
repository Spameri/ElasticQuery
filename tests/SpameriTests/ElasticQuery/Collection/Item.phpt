<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Collection;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * Test implementation of Item interface
 */
class TestItem implements \Spameri\ElasticQuery\Collection\Item
{

	public function __construct(
		private string $key,
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}

}


class Item extends \Tester\TestCase
{

	public function testKey(): void
	{
		$item = new TestItem('test_key');

		\Tester\Assert::same('test_key', $item->key());
	}


	public function testEmptyKey(): void
	{
		$item = new TestItem('');

		\Tester\Assert::same('', $item->key());
	}


	public function testNumericKeyAsString(): void
	{
		$item = new TestItem('123');

		\Tester\Assert::same('123', $item->key());
	}


	public function testSpecialCharactersInKey(): void
	{
		$item = new TestItem('key-with_special.chars:123');

		\Tester\Assert::same('key-with_special.chars:123', $item->key());
	}


	public function testUnicodeKey(): void
	{
		$item = new TestItem('klíč_český');

		\Tester\Assert::same('klíč_český', $item->key());
	}


	public function testImplementsInterface(): void
	{
		$item = new TestItem('key');

		\Tester\Assert::type(\Spameri\ElasticQuery\Collection\Item::class, $item);
	}

}

(new Item())->run();
