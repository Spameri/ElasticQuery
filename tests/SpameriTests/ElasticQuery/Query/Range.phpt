<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Range extends \Tester\TestCase
{

	public function testCreate() : void
	{
		$range = new \Spameri\ElasticQuery\Query\Range(
			'id',
			1,
			1000000,
			1.0
		);

		$array = $range->toArray();

		\Tester\Assert::true(isset($array['range']['id']));
		\Tester\Assert::same(1, $array['range']['id']['gte']);
		\Tester\Assert::same(1000000, $array['range']['id']['lte']);
		\Tester\Assert::same(1.0, $array['range']['id']['boost']);

		$document = new \Spameri\ElasticQuery\Document(
			'spameri_video',
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						new \Spameri\ElasticQuery\Query\MustCollection(
							$range
						)
					)
				)
				)->toArray()
			),
			'spameri_video'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'localhost:9200/_search');
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

}

(new Range())->run();
