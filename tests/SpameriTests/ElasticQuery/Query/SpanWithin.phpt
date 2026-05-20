<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanWithin extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_within';


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
		$span = new \Spameri\ElasticQuery\Query\SpanWithin(
			big: new \Spameri\ElasticQuery\Query\SpanTerm('field', 'a'),
			little: new \Spameri\ElasticQuery\Query\SpanTerm('field', 'b'),
		);

		$array = $span->toArray();

		\Tester\Assert::true(isset($array['span_within']['big']));
		\Tester\Assert::true(isset($array['span_within']['little']));
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanWithin(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'a'),
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'b'),
		);

		\Tester\Assert::same('span_within_span_term_field_a_span_term_field_b', $span->key());
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

(new SpanWithin())->run();
