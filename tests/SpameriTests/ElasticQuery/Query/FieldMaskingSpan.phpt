<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class FieldMaskingSpan extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_field_masking_span';


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
		$span = new \Spameri\ElasticQuery\Query\FieldMaskingSpan(
			query: new \Spameri\ElasticQuery\Query\SpanTerm('text.stems', 'fox'),
			field: 'text',
		);

		$array = $span->toArray();

		\Tester\Assert::same('text', $array['field_masking_span']['field']);
		\Tester\Assert::true(isset($array['field_masking_span']['query']['span_term']));
	}


	public function testKey(): void
	{
		$span = new \Spameri\ElasticQuery\Query\FieldMaskingSpan(
			new \Spameri\ElasticQuery\Query\SpanTerm('text.stems', 'fox'),
			'text',
		);

		\Tester\Assert::same('field_masking_span_text_span_term_text.stems_fox', $span->key());
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

(new FieldMaskingSpan())->run();
