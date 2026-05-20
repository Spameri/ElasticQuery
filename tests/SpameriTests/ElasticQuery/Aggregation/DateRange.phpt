<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class DateRange extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_date_range';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArray(): void
	{
		$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('past', null, 'now-1M/M'),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('recent', 'now-1M/M', null),
		);
		$dateRange = new \Spameri\ElasticQuery\Aggregation\DateRange(
			field: 'created_at',
			ranges: $ranges,
			format: 'MM-yyyy',
		);

		$array = $dateRange->toArray();

		\Tester\Assert::same('created_at', $array['date_range']['field']);
		\Tester\Assert::same('MM-yyyy', $array['date_range']['format']);
		\Tester\Assert::count(2, $array['date_range']['ranges']);
	}


	public function testKey(): void
	{
		$dateRange = new \Spameri\ElasticQuery\Aggregation\DateRange('created_at');

		\Tester\Assert::same('date_range_created_at', $dateRange->key());
	}


	public function testCreate(): void
	{
		$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('past', null, 'now-1M/M'),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('recent', 'now-1M/M', null),
		);
		$dateRange = new \Spameri\ElasticQuery\Aggregation\DateRange(
			field: 'created_at',
			ranges: $ranges,
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'when',
				null,
				$dateRange,
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

(new DateRange())->run();
