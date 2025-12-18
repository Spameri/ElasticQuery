<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class AggregationCollection extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_collection';


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


	public function testConstructorEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();

		\Tester\Assert::same(0, $collection->count());
	}


	public function testConstructorWithAggregations(): void
	{
		$leafCollection1 = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'avg_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);
		$leafCollection2 = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'max_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Max('price'),
		);

		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection(null, $leafCollection1, $leafCollection2);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();
		$leafCollection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'avg_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		$collection->add($leafCollection);

		\Tester\Assert::same(1, $collection->count());
	}


	public function testKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();

		\Tester\Assert::same('top-aggs-collection', $collection->key());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();
		$collection->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'avg_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		));
		$collection->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'max_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Max('price'),
		));

		$keys = $collection->keys();

		\Tester\Assert::contains('avg_price', $keys);
		\Tester\Assert::contains('max_price', $keys);
	}


	public function testFilter(): void
	{
		$filterCollection = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filterCollection->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection($filterCollection);

		\Tester\Assert::same($filterCollection, $collection->filter());
	}


	public function testToArray(): void
	{
		$leafCollection1 = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'avg_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);
		$leafCollection2 = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'max_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Max('price'),
		);

		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection(null, $leafCollection1, $leafCollection2);

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['avg_price']));
		\Tester\Assert::true(isset($array['max_price']));
	}


	public function testCreate(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();
		$collection->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'avg_price',
				null,
				new \Spameri\ElasticQuery\Aggregation\Avg('price'),
			),
		);
		$collection->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'max_price',
				null,
				new \Spameri\ElasticQuery\Aggregation\Max('price'),
			),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			null,
			null,
			null,
			$collection,
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

(new AggregationCollection())->run();
