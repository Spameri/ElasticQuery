<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class LeafAggregationCollection extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_leaf_aggregation_collection';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'my_aggregation',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		\Tester\Assert::same('my_aggregation', $collection->key());
	}


	public function testFilter(): void
	{
		$filterCollection = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filterCollection->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'my_aggregation',
			$filterCollection,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		\Tester\Assert::same($filterCollection, $collection->filter());
	}


	public function testAddAggregation(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'my_aggregation',
			null,
			new \Spameri\ElasticQuery\Aggregation\Term('category'),
		);

		$collection->addAggregation(new \Spameri\ElasticQuery\Aggregation\Avg('price'));

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(2, $items);
	}


	public function testIterate(): void
	{
		$avg = new \Spameri\ElasticQuery\Aggregation\Avg('price');
		$max = new \Spameri\ElasticQuery\Aggregation\Max('price');

		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'my_aggregation',
			null,
			$avg,
			$max,
		);

		$count = 0;
		foreach ($collection as $aggregation) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface::class, $aggregation);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testToArrayWithoutFilter(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'avg_price',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['avg_price']));
		\Tester\Assert::true(isset($array['avg_price']['avg']));
		\Tester\Assert::same('price', $array['avg_price']['avg']['field']);
	}


	public function testToArrayWithFilter(): void
	{
		$filterCollection = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filterCollection->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'filtered_avg',
			$filterCollection,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['filtered_avg']['filter']));
		\Tester\Assert::true(isset($array['filtered_avg']['aggregations']));
	}


	public function testToArrayNestedCollections(): void
	{
		$nestedCollection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'nested_avg',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
		);

		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'parent_agg',
			null,
			new \Spameri\ElasticQuery\Aggregation\Term('category'),
		);
		$collection->addAggregation($nestedCollection);

		$array = $collection->toArray();

		\Tester\Assert::true(isset($array['parent_agg']['aggregations']['nested_avg']));
	}


	public function testCreate(): void
	{
		$collection = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'stats',
			null,
			new \Spameri\ElasticQuery\Aggregation\Avg('price'),
			new \Spameri\ElasticQuery\Aggregation\Min('price'),
			new \Spameri\ElasticQuery\Aggregation\Max('price'),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add($collection);

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
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}

}

(new LeafAggregationCollection())->run();
