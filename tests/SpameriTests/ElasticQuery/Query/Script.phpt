<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Script extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_script';


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
		$script = new \Spameri\ElasticQuery\Query\Script(
			source: "doc['amount'].value > params.threshold",
			params: ['threshold' => 100],
		);

		$array = $script->toArray();

		\Tester\Assert::same(
			"doc['amount'].value > params.threshold",
			$array['script']['script']['source'],
		);
		\Tester\Assert::same(['threshold' => 100], $array['script']['script']['params']);
	}


	public function testKey(): void
	{
		$script = new \Spameri\ElasticQuery\Query\Script('1 == 1');

		\Tester\Assert::contains('script_', $script->key());
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

(new Script())->run();
