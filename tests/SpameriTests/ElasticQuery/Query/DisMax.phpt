<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class DisMax extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_dis_max';


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
		$disMax = new \Spameri\ElasticQuery\Query\DisMax(
			query: new \Spameri\ElasticQuery\Query\Term('title', 'foo'),
			tieBreaker: 0.7,
		);
		$disMax->addQuery(new \Spameri\ElasticQuery\Query\Term('body', 'foo'));

		$array = $disMax->toArray();

		\Tester\Assert::same(0.7, $array['dis_max']['tie_breaker']);
		\Tester\Assert::count(2, $array['dis_max']['queries']);
	}


	public function testKey(): void
	{
		$disMax = new \Spameri\ElasticQuery\Query\DisMax(
			new \Spameri\ElasticQuery\Query\Term('title', 'foo'),
		);

		\Tester\Assert::same('dis_max_term_title_foo', $disMax->key());
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

(new DisMax())->run();
