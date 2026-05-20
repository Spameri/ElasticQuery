<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class CombinedFields extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_combined_fields';


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
		$cf = new \Spameri\ElasticQuery\Query\CombinedFields(
			fields: ['title', 'abstract', 'body'],
			query: 'distributed search',
			operator: 'and',
		);

		$array = $cf->toArray();

		\Tester\Assert::same(['title', 'abstract', 'body'], $array['combined_fields']['fields']);
		\Tester\Assert::same('and', $array['combined_fields']['operator']);
	}


	public function testRequiresFields(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\CombinedFields([], 'x');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$cf = new \Spameri\ElasticQuery\Query\CombinedFields(['title', 'body'], 'q');

		\Tester\Assert::same('combined_fields_title-body_q', $cf->key());
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

(new CombinedFields())->run();
