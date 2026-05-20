<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Wrapper extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_wrapper';


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
		$raw = '{"term":{"user":{"value":"kimchy"}}}';
		$wrapper = new \Spameri\ElasticQuery\Query\Wrapper($raw);

		$array = $wrapper->toArray();

		\Tester\Assert::same(\base64_encode($raw), $array['wrapper']['query']);
	}


	public function testKey(): void
	{
		$wrapper = new \Spameri\ElasticQuery\Query\Wrapper('{}');

		\Tester\Assert::contains('wrapper_', $wrapper->key());
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

(new Wrapper())->run();
