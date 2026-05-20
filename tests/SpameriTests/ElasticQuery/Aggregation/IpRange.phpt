<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class IpRange extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_ip_range';


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
			new \Spameri\ElasticQuery\Aggregation\RangeValue('low', null, '10.0.0.5'),
			new \Spameri\ElasticQuery\Aggregation\RangeValue('high', '10.0.0.5', null),
		);
		$ipRange = new \Spameri\ElasticQuery\Aggregation\IpRange(
			field: 'ip',
			ranges: $ranges,
		);

		$array = $ipRange->toArray();

		\Tester\Assert::same('ip', $array['ip_range']['field']);
		\Tester\Assert::count(2, $array['ip_range']['ranges']);
	}


	public function testKey(): void
	{
		$ipRange = new \Spameri\ElasticQuery\Aggregation\IpRange('ip');

		\Tester\Assert::same('ip_range_ip', $ipRange->key());
	}


	public function testCreate(): void
	{
		$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
			new \Spameri\ElasticQuery\Aggregation\RangeValue('private', null, '10.0.0.0'),
		);
		$ipRange = new \Spameri\ElasticQuery\Aggregation\IpRange(
			field: 'ip',
			ranges: $ranges,
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'ip_ranges',
				null,
				$ipRange,
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

(new IpRange())->run();
