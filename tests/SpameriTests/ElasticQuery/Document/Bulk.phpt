<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Document;

require_once __DIR__ . '/../../bootstrap.php';


class Bulk extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$data = [
			['index' => ['_index' => 'test', '_id' => '1']],
			['field1' => 'value1'],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::true(isset($array['body']));
		\Tester\Assert::same($data, $array['body']);
	}


	public function testToArrayWithMultipleOperations(): void
	{
		$data = [
			['index' => ['_index' => 'products', '_id' => '1']],
			['name' => 'Product 1', 'price' => 10.0],
			['index' => ['_index' => 'products', '_id' => '2']],
			['name' => 'Product 2', 'price' => 20.0],
			['delete' => ['_index' => 'products', '_id' => '3']],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::count(5, $array['body']);
	}


	public function testToArrayWithCreate(): void
	{
		$data = [
			['create' => ['_index' => 'logs', '_id' => 'log_1']],
			['message' => 'Log entry', 'timestamp' => '2024-01-01T00:00:00Z'],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::true(isset($array['body'][0]['create']));
	}


	public function testToArrayWithUpdate(): void
	{
		$data = [
			['update' => ['_index' => 'users', '_id' => 'user_1']],
			['doc' => ['last_login' => '2024-01-15T10:30:00Z']],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::true(isset($array['body'][0]['update']));
		\Tester\Assert::true(isset($array['body'][1]['doc']));
	}


	public function testToArrayWithDelete(): void
	{
		$data = [
			['delete' => ['_index' => 'old_data', '_id' => 'record_1']],
			['delete' => ['_index' => 'old_data', '_id' => 'record_2']],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::count(2, $array['body']);
		\Tester\Assert::true(isset($array['body'][0]['delete']));
		\Tester\Assert::true(isset($array['body'][1]['delete']));
	}


	public function testToArrayEmpty(): void
	{
		$bulk = new \Spameri\ElasticQuery\Document\Bulk([]);

		$array = $bulk->toArray();

		\Tester\Assert::same(['body' => []], $array);
	}


	public function testToArrayWithMixedOperations(): void
	{
		$data = [
			// Index operation
			['index' => ['_index' => 'products', '_id' => '1']],
			['name' => 'New Product'],
			// Update operation
			['update' => ['_index' => 'products', '_id' => '2']],
			['doc' => ['price' => 15.99]],
			// Delete operation
			['delete' => ['_index' => 'products', '_id' => '3']],
			// Create operation
			['create' => ['_index' => 'products', '_id' => '4']],
			['name' => 'Another Product'],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::count(7, $array['body']);
	}


	public function testToArrayWithRouting(): void
	{
		$data = [
			['index' => ['_index' => 'users', '_id' => '1', 'routing' => 'tenant_a']],
			['name' => 'User 1'],
		];
		$bulk = new \Spameri\ElasticQuery\Document\Bulk($data);

		$array = $bulk->toArray();

		\Tester\Assert::same('tenant_a', $array['body'][0]['index']['routing']);
	}

}

(new Bulk())->run();
