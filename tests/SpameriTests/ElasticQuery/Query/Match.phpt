<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Match extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_match';


	public function setUp() : void
	{
		$ch = \curl_init();
		\curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . self::INDEX);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

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
			\Spameri\ElasticQuery\Query\Match\Operator::OR,
			'standard'
		);

		$array = $match->toArray();

		\Tester\Assert::true(isset($array['match']['name']['query']));
		\Tester\Assert::same('Avengers', $array['match']['name']['query']);
		\Tester\Assert::same(1.0, $array['match']['name']['boost']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Operator::OR, $array['match']['name']['operator']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO, $array['match']['name']['fuzziness']);
		\Tester\Assert::same('standard', $array['match']['name']['analyzer']);
		\Tester\Assert::same(2, $array['match']['name']['minimum_should_match']);

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
					)
				)
				)->toArray()
			),
			self::INDEX
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . $document->index() . '/_search');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt(
			$ch, CURLOPT_POSTFIELDS,
			\json_encode($document->toArray()['body'])
		);

		\Tester\Assert::noError(static function () use ($ch) {
			$response = curl_exec($ch);
			$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
			/** @var \Spameri\ElasticQuery\Response\ResultSearch $result */
			$result = $resultMapper->map(\json_decode($response, TRUE));
			\Tester\Assert::type('int', $result->stats()->total());
		});

		curl_close($ch);
	}


	public function tearDown() : void
	{
		$ch = \curl_init();
		\curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . self::INDEX);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}

}

(new Match())->run();
