<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Term extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_term';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}


	public function testToArrayBasic(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category');

		$array = $term->toArray();

		\Tester\Assert::true(isset($array['terms']['field']));
		\Tester\Assert::same('category', $array['terms']['field']);
		\Tester\Assert::false(isset($array['terms']['size']));
	}


	public function testToArrayWithSize(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category', 10);

		$array = $term->toArray();

		\Tester\Assert::same('category', $array['terms']['field']);
		\Tester\Assert::same(10, $array['terms']['size']);
	}


	public function testToArrayWithMissing(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category', 0, 0);

		$array = $term->toArray();

		\Tester\Assert::same('category', $array['terms']['field']);
		\Tester\Assert::same(0, $array['terms']['missing']);
	}


	public function testToArrayWithOrder(): void
	{
		$orderCollection = new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection(
			new \Spameri\ElasticQuery\Aggregation\Terms\Order('_count', 'desc'),
		);
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category', 10, null, $orderCollection);

		$array = $term->toArray();

		\Tester\Assert::same('category', $array['terms']['field']);
		\Tester\Assert::same(['_count' => 'desc'], $array['terms']['order']);
	}


	public function testToArrayWithIncludeExclude(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term(
			'category',
			10,
			null,
			null,
			'product_.*',
			'product_old_.*',
		);

		$array = $term->toArray();

		\Tester\Assert::same('category', $array['terms']['field']);
		\Tester\Assert::same('product_.*', $array['terms']['include']);
		\Tester\Assert::same('product_old_.*', $array['terms']['exclude']);
	}


	public function testKey(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category');

		\Tester\Assert::same('category', $term->key());
	}


	public function testKeyCustom(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term(
			'category',
			0,
			null,
			null,
			null,
			null,
			'custom_key',
		);

		\Tester\Assert::same('custom_key', $term->key());
	}


	public function testCreate(): void
	{
		$term = new \Spameri\ElasticQuery\Aggregation\Term('category', 10);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'categories',
				null,
				$term,
			),
		);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				$elasticQuery->toArray(),
			),
		);

		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . $document->index . '/_search');
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'GET');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		\curl_setopt(
			$ch,
			\CURLOPT_POSTFIELDS,
			\json_encode($document->toArray()['body']),
		);

		\Tester\Assert::noError(static function () use ($ch): void {
			$response = \curl_exec($ch);
			$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
			/** @var \Spameri\ElasticQuery\Response\ResultSearch $result */
			$result = $resultMapper->map(\json_decode($response, true));
			\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultSearch::class, $result);
		});

		\curl_close($ch);
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}

}

(new Term())->run();
