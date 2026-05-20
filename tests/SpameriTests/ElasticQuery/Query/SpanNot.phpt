<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SpanNot extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_span_not';


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
		$span = new \Spameri\ElasticQuery\Query\SpanNot(
			include: new \Spameri\ElasticQuery\Query\SpanTerm('field', 'hot'),
			exclude: new \Spameri\ElasticQuery\Query\SpanTerm('field', 'dog'),
			pre: 0,
			post: 1,
		);

		$array = $span->toArray();

		\Tester\Assert::same(0, $array['span_not']['pre']);
		\Tester\Assert::same(1, $array['span_not']['post']);
		\Tester\Assert::true(isset($array['span_not']['include']));
		\Tester\Assert::true(isset($array['span_not']['exclude']));
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\SpanNot(
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'hot'),
			new \Spameri\ElasticQuery\Query\SpanTerm('field', 'dog'),
		);

		\Tester\Assert::same('span_not_span_term_field_hot_span_term_field_dog', $span->key());
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

(new SpanNot())->run();
