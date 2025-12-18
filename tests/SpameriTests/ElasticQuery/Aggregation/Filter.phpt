<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Filter extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_filter';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayEmpty(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter();

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['filter']['bool']['must']));
		\Tester\Assert::same([], $array['filter']['bool']['must']);
	}


	public function testToArrayWithQuery(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['filter']['bool']['bool']['must']));
		\Tester\Assert::count(1, $array['filter']['bool']['bool']['must']);
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter();

		\Tester\Assert::same('', $filter->key());
	}


	public function testCreateEmpty(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter();

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'all_products',
				null,
				$filter,
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

(new Filter())->run();
