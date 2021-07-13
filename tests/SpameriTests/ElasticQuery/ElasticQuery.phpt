<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

require_once __DIR__ . '/../bootstrap.php';

class ElasticQuery extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_elastic_query';


	public function setUp() : void
	{
		$ch = \curl_init();
		\curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . self::INDEX);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);

		/// ===

		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, 'localhost:9200/' . self::INDEX . '/_mapping');
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		\curl_setopt(
			$ch, \CURLOPT_POSTFIELDS,
			\json_encode([
				'properties' => [
					'name' => [
						'type' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
					],
					'year' => [
						'type' => \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_INTEGER,
					]
				],
			])
		);

		\curl_exec($ch);
		\curl_close($ch);
	}


	public function testCreate() : void
	{
		$match = new \Spameri\ElasticQuery\Query\Match(
			'name',
			'Avengers',
			1.0,
			new \Spameri\ElasticQuery\Query\Match\Fuzziness(
				\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO
			),
			2,
			\Spameri\ElasticQuery\Query\Match\Operator::OR
		);
		$term = new \Spameri\ElasticQuery\Query\Term(
			'name',
			'Avengers',
			1.0
		);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
					new \Spameri\ElasticQuery\ElasticQuery(
						new \Spameri\ElasticQuery\Query\QueryCollection(
							NULL,
							new \Spameri\ElasticQuery\Query\MustCollection(
								$match
							)
						),
						new \Spameri\ElasticQuery\Filter\FilterCollection(
							new \Spameri\ElasticQuery\Query\MustCollection(
								$term
							)
						),
						new \Spameri\ElasticQuery\Options\SortCollection(
							new \Spameri\ElasticQuery\Options\Sort(
								'year',
								\Spameri\ElasticQuery\Options\Sort::ASC
							)
						),
						new \Spameri\ElasticQuery\Aggregation\AggregationCollection(
							new \Spameri\ElasticQuery\Filter\FilterCollection(
								new \Spameri\ElasticQuery\Query\MustCollection(
									$term
								)
							),
							new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
								'year_agg',
								NULL,
								new \Spameri\ElasticQuery\Aggregation\Term(
									'year'
								)
							)
						),
						NULL,
						NULL,
						new \Spameri\ElasticQuery\Options(
							10,
							1
						)
					)
				)->toArray()
			)
		);

		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, 'localhost:9200/' . $document->index() . '/_search');
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'GET');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		\curl_setopt(
			$ch, \CURLOPT_POSTFIELDS,
			\json_encode($document->toArray()['body'])
		);

		\Tester\Assert::noError(static function () use ($ch) {
			$response = \curl_exec($ch);
			$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
			/** @var \Spameri\ElasticQuery\Response\ResultSearch $result */
			$result = $resultMapper->map(\json_decode($response, TRUE));
			\Tester\Assert::type('int', $result->stats()->total());
		});

		\curl_close($ch);
	}


	public function tearDown() : void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, 'localhost:9200/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}

}

(new ElasticQuery())->run();
