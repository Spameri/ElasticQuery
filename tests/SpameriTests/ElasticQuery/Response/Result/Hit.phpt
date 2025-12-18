<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class Hit extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test', 'price' => 100],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.5,
			1,
		);

		\Tester\Assert::same(['name' => 'Test', 'price' => 100], $hit->source());
		\Tester\Assert::same(0, $hit->position());
		\Tester\Assert::same('test_index', $hit->index());
		\Tester\Assert::same('_doc', $hit->type());
		\Tester\Assert::same('doc_1', $hit->id());
		\Tester\Assert::same(1.5, $hit->score());
		\Tester\Assert::same(1, $hit->version());
	}


	public function testGetValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test', 'price' => 100, 'active' => true],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same('Test', $hit->getValue('name'));
		\Tester\Assert::same(100, $hit->getValue('price'));
		\Tester\Assert::same(true, $hit->getValue('active'));
		\Tester\Assert::null($hit->getValue('nonexistent'));
	}


	public function testGetStringValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same('Test', $hit->getStringValue('name'));
	}


	public function testGetStringValueThrowsOnNonString(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['price' => 100],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::exception(
			static function () use ($hit): void {
				$hit->getStringValue('price');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testGetStringOrNullValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test', 'price' => 100],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same('Test', $hit->getStringOrNullValue('name'));
		\Tester\Assert::null($hit->getStringOrNullValue('price'));
		\Tester\Assert::null($hit->getStringOrNullValue('nonexistent'));
	}


	public function testGetArrayValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['tags' => ['php', 'elasticsearch']],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(['php', 'elasticsearch'], $hit->getArrayValue('tags'));
	}


	public function testGetArrayValueThrowsOnNonArray(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::exception(
			static function () use ($hit): void {
				$hit->getArrayValue('name');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testGetArrayOrNullValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['tags' => ['php'], 'name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(['php'], $hit->getArrayOrNullValue('tags'));
		\Tester\Assert::null($hit->getArrayOrNullValue('name'));
	}


	public function testGetBoolValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['active' => true],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::true($hit->getBoolValue('active'));
	}


	public function testGetBoolOrNullValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['active' => false, 'name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::false($hit->getBoolOrNullValue('active'));
		\Tester\Assert::null($hit->getBoolOrNullValue('name'));
	}


	public function testGetIntegerValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['count' => 42],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(42, $hit->getIntegerValue('count'));
	}


	public function testGetIntegerOrNullValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['count' => 42, 'name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(42, $hit->getIntegerOrNullValue('count'));
		\Tester\Assert::null($hit->getIntegerOrNullValue('name'));
	}


	public function testGetFloatValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['price' => 99.99],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(99.99, $hit->getFloatValue('price'));
	}


	public function testGetFloatOrNullValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['price' => 99.99, 'name' => 'Test'],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same(99.99, $hit->getFloatOrNullValue('price'));
		\Tester\Assert::null($hit->getFloatOrNullValue('name'));
	}


	public function testGetSubValue(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['nested' => ['level1' => ['level2' => 'deep_value']]],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::same('deep_value', $hit->getValue('nested.level1.level2'));
	}


	public function testGetSubValueReturnsNull(): void
	{
		$hit = new \Spameri\ElasticQuery\Response\Result\Hit(
			['nested' => ['level1' => 'value']],
			0,
			'test_index',
			'_doc',
			'doc_1',
			1.0,
			1,
		);

		\Tester\Assert::null($hit->getValue('nested.nonexistent.path'));
	}

}

(new Hit())->run();
