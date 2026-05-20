<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class AdjacencyMatrix extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_adjacency_matrix';


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
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix();
		$matrix->addFilter('active', $filter);

		$array = $matrix->toArray();

		\Tester\Assert::true(isset($array['adjacency_matrix']['filters']['active']));
		\Tester\Assert::true(isset($array['adjacency_matrix']['filters']['active']['bool']));
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'adjacency_matrix',
			(new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix())->key(),
		);
	}


	public function testCreate(): void
	{
		$filterA = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filterA->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix();
		$matrix->addFilter('group_a', $filterA);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'matrix',
				null,
				$matrix,
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

(new AdjacencyMatrix())->run();
