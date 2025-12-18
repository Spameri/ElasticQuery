<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Range extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_range';


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
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price');

		$array = $range->toArray();

		\Tester\Assert::true(isset($array['range']['field']));
		\Tester\Assert::same('price', $array['range']['field']);
		\Tester\Assert::false(isset($array['range']['keyed']));
	}


	public function testToArrayWithKeyed(): void
	{
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price', true);

		$array = $range->toArray();

		\Tester\Assert::same('price', $array['range']['field']);
		\Tester\Assert::true($array['range']['keyed']);
	}


	public function testToArrayWithRanges(): void
	{
		$rangeCollection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('cheap', null, 50),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('medium', 50, 100),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('expensive', 100, null),
		);
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price', false, $rangeCollection);

		$array = $range->toArray();

		\Tester\Assert::same('price', $array['range']['field']);
		\Tester\Assert::count(3, $array['range']['ranges']);
		\Tester\Assert::same('cheap', $array['range']['ranges'][0]['key']);
		\Tester\Assert::same('medium', $array['range']['ranges'][1]['key']);
		\Tester\Assert::same('expensive', $array['range']['ranges'][2]['key']);
	}


	public function testKey(): void
	{
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price');

		\Tester\Assert::same('price', $range->key());
	}


	public function testRanges(): void
	{
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price');
		$range->ranges()->add(new \Spameri\ElasticQuery\Aggregation\RangeValue('low', null, 50));

		$array = $range->toArray();

		\Tester\Assert::count(1, $array['range']['ranges']);
	}


	public function testCreate(): void
	{
		$rangeCollection = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('cheap', null, 50),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('expensive', 50, null),
		);
		$range = new \Spameri\ElasticQuery\Aggregation\Range('price', false, $rangeCollection);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price_ranges',
				null,
				$range,
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

(new Range())->run();
