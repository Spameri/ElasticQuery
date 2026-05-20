<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class ReverseNested extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_reverse_nested';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayWithoutPath(): void
	{
		$reverseNested = new \Spameri\ElasticQuery\Aggregation\ReverseNested();

		$array = $reverseNested->toArray();

		\Tester\Assert::true(isset($array['reverse_nested']));
		\Tester\Assert::type(\stdClass::class, $array['reverse_nested']);
	}


	public function testToArrayWithPath(): void
	{
		$reverseNested = new \Spameri\ElasticQuery\Aggregation\ReverseNested('parent');

		$array = $reverseNested->toArray();

		\Tester\Assert::same('parent', $array['reverse_nested']['path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'reverse_nested_root',
			(new \Spameri\ElasticQuery\Aggregation\ReverseNested())->key(),
		);
		\Tester\Assert::same(
			'reverse_nested_parent',
			(new \Spameri\ElasticQuery\Aggregation\ReverseNested('parent'))->key(),
		);
	}


	public function testCreate(): void
	{
		$reverseNested = new \Spameri\ElasticQuery\Aggregation\ReverseNested();

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'back',
				null,
				$reverseNested,
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

(new ReverseNested())->run();
