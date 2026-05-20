<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Boosting extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_boosting';


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
		$boosting = new \Spameri\ElasticQuery\Query\Boosting(
			positive: new \Spameri\ElasticQuery\Query\Term('text', 'apple'),
			negative: new \Spameri\ElasticQuery\Query\Term('text', 'pie'),
			negativeBoost: 0.5,
		);

		$array = $boosting->toArray();

		\Tester\Assert::same(0.5, $array['boosting']['negative_boost']);
		\Tester\Assert::same('apple', $array['boosting']['positive']['term']['text']['value']);
		\Tester\Assert::same('pie', $array['boosting']['negative']['term']['text']['value']);
	}


	public function testKey(): void
	{
		$boosting = new \Spameri\ElasticQuery\Query\Boosting(
			new \Spameri\ElasticQuery\Query\Term('text', 'a'),
			new \Spameri\ElasticQuery\Query\Term('text', 'b'),
			0.5,
		);

		\Tester\Assert::same('boosting_term_text_a_term_text_b', $boosting->key());
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

(new Boosting())->run();
