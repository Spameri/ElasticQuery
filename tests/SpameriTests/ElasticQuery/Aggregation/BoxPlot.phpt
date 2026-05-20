<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class BoxPlot extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_boxplot';


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
		$boxPlot = new \Spameri\ElasticQuery\Aggregation\BoxPlot('load_time');

		$array = $boxPlot->toArray();

		\Tester\Assert::same('load_time', $array['boxplot']['field']);
		\Tester\Assert::false(isset($array['boxplot']['compression']));
	}


	public function testToArrayWithCompression(): void
	{
		$boxPlot = new \Spameri\ElasticQuery\Aggregation\BoxPlot('load_time', 200);

		$array = $boxPlot->toArray();

		\Tester\Assert::same(200, $array['boxplot']['compression']);
	}


	public function testKey(): void
	{
		$boxPlot = new \Spameri\ElasticQuery\Aggregation\BoxPlot('load_time');

		\Tester\Assert::same('boxplot_load_time', $boxPlot->key());
	}


	public function testCreate(): void
	{
		$boxPlot = new \Spameri\ElasticQuery\Aggregation\BoxPlot('load_time');

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'load_time_boxplot',
				null,
				$boxPlot,
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

(new BoxPlot())->run();
