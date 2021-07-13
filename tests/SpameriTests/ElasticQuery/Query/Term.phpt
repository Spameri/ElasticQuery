<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Term extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_term';


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
		$term = new \Spameri\ElasticQuery\Query\Term(
			'name',
			'Avengers',
			1.0
		);

		$array = $term->toArray();

		\Tester\Assert::true(isset($array['term']['name']['value']));
		\Tester\Assert::same('Avengers', $array['term']['name']['value']);
		\Tester\Assert::same(1.0, $array['term']['name']['boost']);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						NULL,
						new \Spameri\ElasticQuery\Query\MustCollection(
							$term
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

(new Term())->run();
