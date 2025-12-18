<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

require_once __DIR__ . '/../bootstrap.php';


class Document extends \Tester\TestCase
{

	public function testToArrayWithIndexOnly(): void
	{
		$document = new \Spameri\ElasticQuery\Document('test_index');

		$array = $document->toArray();

		\Tester\Assert::same(['index' => 'test_index'], $array);
	}


	public function testToArrayWithIndexAndId(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: 'products',
			id: 'product_123',
		);

		$array = $document->toArray();

		\Tester\Assert::same('products', $array['index']);
		\Tester\Assert::same('product_123', $array['id']);
	}


	public function testToArrayWithBody(): void
	{
		$body = new \Spameri\ElasticQuery\Document\Body\Plain(['query' => ['match_all' => new \stdClass()]]);
		$document = new \Spameri\ElasticQuery\Document(
			index: 'search_index',
			body: $body,
		);

		$array = $document->toArray();

		\Tester\Assert::same('search_index', $array['index']);
		\Tester\Assert::true(isset($array['body']));
		\Tester\Assert::true(isset($array['body']['query']));
	}


	public function testToArrayWithOptions(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: 'test_index',
			options: ['refresh' => true, 'routing' => 'user_1'],
		);

		$array = $document->toArray();

		\Tester\Assert::same('test_index', $array['index']);
		\Tester\Assert::same(true, $array['refresh']);
		\Tester\Assert::same('user_1', $array['routing']);
	}


	public function testToArrayFull(): void
	{
		$body = new \Spameri\ElasticQuery\Document\Body\Plain(['title' => 'Test Product', 'price' => 99.99]);
		$document = new \Spameri\ElasticQuery\Document(
			index: 'products',
			body: $body,
			id: 'prod_456',
			options: ['refresh' => 'wait_for'],
		);

		$array = $document->toArray();

		\Tester\Assert::same('products', $array['index']);
		\Tester\Assert::same('prod_456', $array['id']);
		\Tester\Assert::same(['title' => 'Test Product', 'price' => 99.99], $array['body']);
		\Tester\Assert::same('wait_for', $array['refresh']);
	}


	public function testToArrayWithNullIndex(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: null,
			id: 'doc_id',
		);

		$array = $document->toArray();

		\Tester\Assert::false(isset($array['index']));
		\Tester\Assert::same('doc_id', $array['id']);
	}


	public function testToArrayWithNullId(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: 'my_index',
			id: null,
		);

		$array = $document->toArray();

		\Tester\Assert::same('my_index', $array['index']);
		\Tester\Assert::false(isset($array['id']));
	}


	public function testToArrayWithNullBody(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: 'my_index',
			body: null,
		);

		$array = $document->toArray();

		\Tester\Assert::false(isset($array['body']));
	}


	public function testToArrayWithEmptyOptions(): void
	{
		$document = new \Spameri\ElasticQuery\Document(
			index: 'my_index',
			options: [],
		);

		$array = $document->toArray();

		\Tester\Assert::same(['index' => 'my_index'], $array);
	}


	public function testPublicProperties(): void
	{
		$body = new \Spameri\ElasticQuery\Document\Body\Plain(['data' => 'value']);
		$document = new \Spameri\ElasticQuery\Document(
			index: 'test_index',
			body: $body,
			id: 'test_id',
			options: ['timeout' => '5m'],
		);

		\Tester\Assert::same('test_index', $document->index);
		\Tester\Assert::same($body, $document->body);
		\Tester\Assert::same('test_id', $document->id);
		\Tester\Assert::same(['timeout' => '5m'], $document->options);
	}


	public function testWithElasticQueryBody(): void
	{
		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->query()->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$body = new \Spameri\ElasticQuery\Document\Body\Plain($elasticQuery->toArray());
		$document = new \Spameri\ElasticQuery\Document(
			index: 'products',
			body: $body,
		);

		$array = $document->toArray();

		\Tester\Assert::true(isset($array['body']['query']));
	}

}

(new Document())->run();
