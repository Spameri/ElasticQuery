<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanNear extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_near';


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
		$span = new \Spameri\ElasticQuery\Query\SpanNear(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value1'),
			slop: 12,
			inOrder: false,
		);
		$span->addClause(new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value2'));

		$array = $span->toArray();

		\Tester\Assert::same(12, $array['span_near']['slop']);
		\Tester\Assert::false($array['span_near']['in_order']);
		\Tester\Assert::count(2, $array['span_near']['clauses']);
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanNear(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'value1'),
		);

		\Tester\Assert::same('span_near_span_term_field_value1', $span->key());
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

(new SpanNear())->run();
