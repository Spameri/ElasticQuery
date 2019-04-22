<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class WildCard extends \Tester\TestCase
{

	public function testCreate() : void
	{
		$wildCard = new \Spameri\ElasticQuery\Query\WildCard(
			'name',
			'Avengers',
			1.0
		);

		$array = $wildCard->toArray();

		\Tester\Assert::true(isset($array['wildcard']['name']['value']));
		\Tester\Assert::same('Avengers', $array['wildcard']['name']['value']);
		\Tester\Assert::same(1.0, $array['wildcard']['name']['boost']);

		$document = new \Spameri\ElasticQuery\Document(
			'spameri_video',
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						new \Spameri\ElasticQuery\Query\MustCollection(
							$wildCard
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

(new WildCard())->run();
