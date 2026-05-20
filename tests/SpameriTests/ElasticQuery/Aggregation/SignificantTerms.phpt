<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class SignificantTerms extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_significant_terms';


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
		$significant = new \Spameri\ElasticQuery\Aggregation\SignificantTerms('crime_type');

		$array = $significant->toArray();

		\Tester\Assert::same('crime_type', $array['significant_terms']['field']);
		\Tester\Assert::false(isset($array['significant_terms']['size']));
	}


	public function testToArrayWithSize(): void
	{
		$significant = new \Spameri\ElasticQuery\Aggregation\SignificantTerms('crime_type', 10, 5);

		$array = $significant->toArray();

		\Tester\Assert::same(10, $array['significant_terms']['size']);
		\Tester\Assert::same(5, $array['significant_terms']['min_doc_count']);
	}


	public function testKey(): void
	{
		$significant = new \Spameri\ElasticQuery\Aggregation\SignificantTerms('crime_type');

		\Tester\Assert::same('significant_terms_crime_type', $significant->key());
	}


	public function testCreate(): void
	{
		$significant = new \Spameri\ElasticQuery\Aggregation\SignificantTerms('crime_type');

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'unusual_crimes',
				null,
				$significant,
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

(new SignificantTerms())->run();
