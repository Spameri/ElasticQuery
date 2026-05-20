<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SimpleQueryString extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_simple_query_string';


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
		$sqs = new \Spameri\ElasticQuery\Query\SimpleQueryString(
			query: 'foo + bar -baz',
			fields: ['title^2', 'body'],
		);

		$array = $sqs->toArray();

		\Tester\Assert::same('foo + bar -baz', $array['simple_query_string']['query']);
		\Tester\Assert::same(['title^2', 'body'], $array['simple_query_string']['fields']);
	}


	public function testKey(): void
	{
		$sqs = new \Spameri\ElasticQuery\Query\SimpleQueryString('q');

		\Tester\Assert::same('simple_query_string_q', $sqs->key());
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

(new SimpleQueryString())->run();
