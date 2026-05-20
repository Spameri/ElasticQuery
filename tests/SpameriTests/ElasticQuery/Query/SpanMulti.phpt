<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanMulti extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_multi';


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
		$span = new \Spameri\ElasticQuery\Query\SpanMulti(
			match: new \Spameri\ElasticQuery\Query\Prefix(field: 'user', query: 'ki'),
		);

		$array = $span->toArray();

		\Tester\Assert::true(isset($array['span_multi']['match']['prefix']));
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanMulti(
			match: new \Spameri\ElasticQuery\Query\Prefix('user', 'ki'),
		);

		\Tester\Assert::same('span_multi_prefix_user_ki', $span->key());
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

(new SpanMulti())->run();
