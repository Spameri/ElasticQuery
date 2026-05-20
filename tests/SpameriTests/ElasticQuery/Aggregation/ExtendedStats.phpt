<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class ExtendedStats extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_extended_stats';


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
		$extendedStats = new \Spameri\ElasticQuery\Aggregation\ExtendedStats('price');

		$array = $extendedStats->toArray();

		\Tester\Assert::true(isset($array['extended_stats']['field']));
		\Tester\Assert::same('price', $array['extended_stats']['field']);
		\Tester\Assert::false(isset($array['extended_stats']['sigma']));
	}


	public function testToArrayWithSigma(): void
	{
		$extendedStats = new \Spameri\ElasticQuery\Aggregation\ExtendedStats('price', 3.0);

		$array = $extendedStats->toArray();

		\Tester\Assert::same(3.0, $array['extended_stats']['sigma']);
	}


	public function testKey(): void
	{
		$extendedStats = new \Spameri\ElasticQuery\Aggregation\ExtendedStats('price');

		\Tester\Assert::same('extended_stats_price', $extendedStats->key());
	}


	public function testCreate(): void
	{
		$extendedStats = new \Spameri\ElasticQuery\Aggregation\ExtendedStats('price');

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price_extended_stats',
				null,
				$extendedStats,
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

(new ExtendedStats())->run();
