<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Composite extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_composite';


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
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'my_buckets',
			source: new \Spameri\ElasticQuery\Aggregation\Term('product'),
			size: 100,
		);
		$composite->addSource(new \Spameri\ElasticQuery\Aggregation\Histogram('price', 50));

		$array = $composite->toArray();

		\Tester\Assert::same(100, $array['composite']['size']);
		\Tester\Assert::count(2, $array['composite']['sources']);
		\Tester\Assert::same('product', $array['composite']['sources'][0]['product']['terms']['field']);
		\Tester\Assert::same('price', $array['composite']['sources'][1]['price']['histogram']['field']);
	}


	public function testToArrayWithAfter(): void
	{
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'my_buckets',
			source: new \Spameri\ElasticQuery\Aggregation\Term('product'),
			after: ['product' => 'foo'],
		);

		$array = $composite->toArray();

		\Tester\Assert::same(['product' => 'foo'], $array['composite']['after']);
	}


	public function testKey(): void
	{
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'my_buckets',
			source: new \Spameri\ElasticQuery\Aggregation\Term('product'),
		);

		\Tester\Assert::same('my_buckets', $composite->key());
	}


	public function testCreate(): void
	{
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'my_buckets',
			source: new \Spameri\ElasticQuery\Aggregation\Term('product'),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'composite_agg',
				null,
				$composite,
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

(new Composite())->run();
