<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class GeoDistance extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_geo_distance';


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
			new \Spameri\ElasticQuery\Aggregation\RangeValue('near', null, 100),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('far', 100, null),
		);
		$geoDistance = new \Spameri\ElasticQuery\Aggregation\GeoDistance(
			field: 'location',
			lat: 50.0,
			lon: 14.4,
			ranges: $ranges,
			unit: 'km',
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same('location', $array['geo_distance']['field']);
		\Tester\Assert::same(50.0, $array['geo_distance']['origin']['lat']);
		\Tester\Assert::same(14.4, $array['geo_distance']['origin']['lon']);
		\Tester\Assert::same('km', $array['geo_distance']['unit']);
		\Tester\Assert::count(2, $array['geo_distance']['ranges']);
	}


	public function testKey(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Aggregation\GeoDistance(
			field: 'location',
			lat: 50.0,
			lon: 14.4,
		);

		\Tester\Assert::same('geo_distance_location', $geoDistance->key());
	}


	public function testCreate(): void
	{
		$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('near', null, 100),
		);
		$geoDistance = new \Spameri\ElasticQuery\Aggregation\GeoDistance(
			field: 'location',
			lat: 50.0,
			lon: 14.4,
			ranges: $ranges,
			unit: 'km',
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'rings',
				null,
				$geoDistance,
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

(new GeoDistance())->run();
