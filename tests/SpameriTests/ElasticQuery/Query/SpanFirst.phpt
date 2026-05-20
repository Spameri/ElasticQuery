<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanFirst extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_first';


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
		$span = new \Spameri\ElasticQuery\Query\SpanFirst(
			match: new \Spameri\ElasticQuery\Query\SpanTerm('user', 'kimchy'),
			end: 3,
		);

		$array = $span->toArray();

		\Tester\Assert::same(3, $array['span_first']['end']);
		\Tester\Assert::true(isset($array['span_first']['match']['span_term']));
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanFirst(
			new \Spameri\ElasticQuery\Query\SpanTerm('user', 'kimchy'),
			3,
		);

		\Tester\Assert::same('span_first_span_term_user_kimchy_3', $span->key());
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

(new SpanFirst())->run();
