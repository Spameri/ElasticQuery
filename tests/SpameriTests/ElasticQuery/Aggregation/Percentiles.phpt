<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Percentiles extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_percentiles';


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
		$percentiles = new \Spameri\ElasticQuery\Aggregation\Percentiles('load_time');

		$array = $percentiles->toArray();

		\Tester\Assert::true(isset($array['percentiles']['field']));
		\Tester\Assert::same('load_time', $array['percentiles']['field']);
	}


	public function testToArrayWithPercents(): void
	{
		$percentiles = new \Spameri\ElasticQuery\Aggregation\Percentiles(
			'load_time',
			[50, 95, 99],
		);

		$array = $percentiles->toArray();

		\Tester\Assert::same([50, 95, 99], $array['percentiles']['percents']);
	}


	public function testToArrayUnkeyed(): void
	{
		$percentiles = new \Spameri\ElasticQuery\Aggregation\Percentiles(
			'load_time',
			[],
			false,
		);

		$array = $percentiles->toArray();

		\Tester\Assert::false($array['percentiles']['keyed']);
	}


	public function testKey(): void
	{
		$percentiles = new \Spameri\ElasticQuery\Aggregation\Percentiles('load_time');

		\Tester\Assert::same('percentiles_load_time', $percentiles->key());
	}


	public function testCreate(): void
	{
		$percentiles = new \Spameri\ElasticQuery\Aggregation\Percentiles('load_time');

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'load_time_percentiles',
				null,
				$percentiles,
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

(new Percentiles())->run();
