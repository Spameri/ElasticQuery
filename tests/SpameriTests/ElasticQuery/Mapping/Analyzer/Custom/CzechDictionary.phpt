<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer\Custom;


require_once __DIR__ . '/../../../../bootstrap.php';


class CzechDictionary extends \Tester\TestCase
{

	private const INDEX = 'spameri_czech_dictionary_video';


	public function testCreate() : void
	{
		$settings = new \Spameri\ElasticQuery\Mapping\Settings(self::INDEX);
		$settings->addAnalyzer(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\CzechDictionary());
		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				$settings->toArray()
			)
		);

		// Set up index and analyzer

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . $document->index());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt(
			$ch, CURLOPT_POSTFIELDS,
			\json_encode($document->toArray()['body'])
		);

		curl_exec($ch);

		// Fetch settings and test if analyzer is configured

		curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . $document->index() . '/_settings');
		curl_setopt($ch, CURLOPT_POSTFIELDS, []);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		$responseSettings = \json_decode(curl_exec($ch), TRUE);

		\Tester\Assert::true(isset(
			$responseSettings[self::INDEX]['settings']['index']['analysis']['analyzer']['czechDictionary']
		));
		\Tester\Assert::same(
			'dictionary_CZ',
			$responseSettings[self::INDEX]['settings']['index']['analysis']['analyzer']['czechDictionary']['filter'][2]
		);
		\Tester\Assert::same(
			'custom',
			$responseSettings[self::INDEX]['settings']['index']['analysis']['analyzer']['czechDictionary']['type']
		);

		// Analyze text and test if output is as expected

		$text = 'Playstation 4 je nejlepší se SodaStream drinkem a kouskem GS-condro!';

		curl_setopt($ch, CURLOPT_URL, 'localhost:9200/' . $document->index() . '/_analyze');
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
			'text' => $text,
			'analyzer' => 'czechDictionary',
		]);

		$responseAnalyzer = \json_decode(curl_exec($ch), TRUE);

		curl_close($ch);
	}


	protected function tearDown(): void
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

(new CzechDictionary())->run();
