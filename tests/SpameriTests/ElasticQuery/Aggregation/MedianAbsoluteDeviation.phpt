<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class MedianAbsoluteDeviation extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_median_absolute_deviation';


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
		$mad = new \Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation('rating');

		$array = $mad->toArray();

		\Tester\Assert::same('rating', $array['median_absolute_deviation']['field']);
		\Tester\Assert::false(isset($array['median_absolute_deviation']['compression']));
	}


	public function testToArrayWithCompression(): void
	{
		$mad = new \Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation('rating', 200);

		$array = $mad->toArray();

		\Tester\Assert::same(200, $array['median_absolute_deviation']['compression']);
	}


	public function testKey(): void
	{
		$mad = new \Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation('rating');

		\Tester\Assert::same('median_absolute_deviation_rating', $mad->key());
	}


	public function testCreate(): void
	{
		$mad = new \Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation('rating');

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'rating_mad',
				null,
				$mad,
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

(new MedianAbsoluteDeviation())->run();
