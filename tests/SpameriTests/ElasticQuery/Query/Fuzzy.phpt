<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Fuzzy extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_fuzzy';


	public function setUp() : void
	{
		$ch = \curl_init();
		\curl_setopt($ch, CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testCreate() : void
	{
		$fuzzy = new \Spameri\ElasticQuery\Query\Fuzzy(
			'name',
			'Avengers',
			1.0,
			2,
			0,
			100
		);

		$array = $fuzzy->toArray();

		\Tester\Assert::true(isset($array['fuzzy']['name']['value']));
		\Tester\Assert::same('Avengers', $array['fuzzy']['name']['value']);
		\Tester\Assert::same(1.0, $array['fuzzy']['name']['boost']);
		\Tester\Assert::same(2, $array['fuzzy']['name']['fuzziness']);
		\Tester\Assert::same(0, $array['fuzzy']['name']['prefix_length']);
		\Tester\Assert::same(100, $array['fuzzy']['name']['max_expansions']);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						NULL,
						new \Spameri\ElasticQuery\Query\MustCollection(
							$fuzzy
						)
					)
				)
				)->toArray()
			)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . $document->index . '/_search');
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
		\curl_setopt($ch, CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}

}

(new Fuzzy())->run();
