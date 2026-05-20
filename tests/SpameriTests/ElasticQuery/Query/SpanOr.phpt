<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanOr extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_or';


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
		$span = new \Spameri\ElasticQuery\Query\SpanOr(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value1'),
		);
		$span->addClause(new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value2'));

		$array = $span->toArray();

		\Tester\Assert::count(2, $array['span_or']['clauses']);
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanOr(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value1'),
		);

		\Tester\Assert::same('span_or_span_term_field_value1', $span->key());
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

(new SpanOr())->run();
