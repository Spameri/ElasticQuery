<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Document\Body;

require_once __DIR__ . '/../../../bootstrap.php';


class Plain extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain(['key' => 'value']);

		$array = $plain->toArray();

		\Tester\Assert::same(['key' => 'value'], $array);
	}


	public function testToArrayEmpty(): void
	{
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain([]);

		$array = $plain->toArray();

		\Tester\Assert::same([], $array);
	}


	public function testToArrayWithNestedData(): void
	{
		$data = [
			'query' => [
				'bool' => [
					'must' => [
						['term' => ['status' => 'active']],
					],
				],
			],
		];
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($data);

		$array = $plain->toArray();

		\Tester\Assert::same($data, $array);
	}


	public function testToArrayWithMultipleKeys(): void
	{
		$data = [
			'title' => 'Test Document',
			'content' => 'Some content here',
			'tags' => ['php', 'elasticsearch'],
			'published' => true,
			'views' => 1234,
		];
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($data);

		$array = $plain->toArray();

		\Tester\Assert::same('Test Document', $array['title']);
		\Tester\Assert::same('Some content here', $array['content']);
		\Tester\Assert::same(['php', 'elasticsearch'], $array['tags']);
		\Tester\Assert::same(true, $array['published']);
		\Tester\Assert::same(1234, $array['views']);
	}


	public function testToArrayWithElasticQueryOutput(): void
	{
		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->query()->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'published'));
		$elasticQuery->options()->changeSize(10);

		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($elasticQuery->toArray());

		$array = $plain->toArray();

		\Tester\Assert::true(isset($array['query']));
		\Tester\Assert::true(isset($array['size']));
	}


	public function testToArrayPreservesStructure(): void
	{
		$data = [
			'aggs' => [
				'categories' => [
					'terms' => [
						'field' => 'category',
						'size' => 10,
					],
				],
			],
		];
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($data);

		$array = $plain->toArray();

		\Tester\Assert::same(10, $array['aggs']['categories']['terms']['size']);
	}


	public function testToArrayWithNumericKeys(): void
	{
		$data = [
			0 => 'first',
			1 => 'second',
			2 => 'third',
		];
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($data);

		$array = $plain->toArray();

		\Tester\Assert::same(['first', 'second', 'third'], $array);
	}


	public function testToArrayWithMixedTypes(): void
	{
		$data = [
			'string' => 'text',
			'int' => 42,
			'float' => 3.14,
			'bool' => false,
			'null' => null,
			'array' => [1, 2, 3],
		];
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain($data);

		$array = $plain->toArray();

		\Tester\Assert::same('text', $array['string']);
		\Tester\Assert::same(42, $array['int']);
		\Tester\Assert::same(3.14, $array['float']);
		\Tester\Assert::same(false, $array['bool']);
		\Tester\Assert::null($array['null']);
		\Tester\Assert::same([1, 2, 3], $array['array']);
	}


	public function testImplementsBodyInterface(): void
	{
		$plain = new \Spameri\ElasticQuery\Document\Body\Plain([]);

		\Tester\Assert::type(\Spameri\ElasticQuery\Document\BodyInterface::class, $plain);
	}

}

(new Plain())->run();
