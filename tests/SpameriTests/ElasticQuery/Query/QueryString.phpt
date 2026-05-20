<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class QueryString extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_query_string';


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
		$qs = new \Spameri\ElasticQuery\Query\QueryString(
			query: '(new york city) OR (big apple)',
			defaultField: 'content',
		);

		$array = $qs->toArray();

		\Tester\Assert::same('(new york city) OR (big apple)', $array['query_string']['query']);
		\Tester\Assert::same('content', $array['query_string']['default_field']);
	}


	public function testKey(): void
	{
		$qs = new \Spameri\ElasticQuery\Query\QueryString('foo');

		\Tester\Assert::same('query_string_foo', $qs->key());
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

(new QueryString())->run();
