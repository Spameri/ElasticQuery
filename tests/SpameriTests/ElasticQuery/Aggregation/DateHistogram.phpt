<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class DateHistogram extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_date_histogram';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayCalendarInterval(): void
	{
		$dateHistogram = new \Spameri\ElasticQuery\Aggregation\DateHistogram(
			field: 'created_at',
			calendarInterval: 'month',
		);

		$array = $dateHistogram->toArray();

		\Tester\Assert::same('created_at', $array['date_histogram']['field']);
		\Tester\Assert::same('month', $array['date_histogram']['calendar_interval']);
		\Tester\Assert::false(isset($array['date_histogram']['fixed_interval']));
	}


	public function testToArrayFixedInterval(): void
	{
		$dateHistogram = new \Spameri\ElasticQuery\Aggregation\DateHistogram(
			field: 'created_at',
			fixedInterval: '7d',
			format: 'yyyy-MM-dd',
			timeZone: 'Europe/Prague',
			minDocCount: 1,
		);

		$array = $dateHistogram->toArray();

		\Tester\Assert::same('7d', $array['date_histogram']['fixed_interval']);
		\Tester\Assert::same('yyyy-MM-dd', $array['date_histogram']['format']);
		\Tester\Assert::same('Europe/Prague', $array['date_histogram']['time_zone']);
		\Tester\Assert::same(1, $array['date_histogram']['min_doc_count']);
	}


	public function testRequiresInterval(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\DateHistogram('created_at');
			},
			\InvalidArgumentException::class,
		);
	}


	public function testRejectsBothIntervals(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\DateHistogram(
					field: 'created_at',
					calendarInterval: 'month',
					fixedInterval: '30d',
				);
			},
			\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$dateHistogram = new \Spameri\ElasticQuery\Aggregation\DateHistogram(
			field: 'created_at',
			calendarInterval: 'month',
		);

		\Tester\Assert::same('date_histogram_created_at', $dateHistogram->key());
	}


	public function testCreate(): void
	{
		$dateHistogram = new \Spameri\ElasticQuery\Aggregation\DateHistogram(
			field: 'created_at',
			calendarInterval: 'month',
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'over_time',
				null,
				$dateHistogram,
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

(new DateHistogram())->run();
