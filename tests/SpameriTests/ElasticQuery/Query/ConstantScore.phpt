<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class ConstantScore extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_constant_score';


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
		$cs = new \Spameri\ElasticQuery\Query\ConstantScore(
			filter: new \Spameri\ElasticQuery\Query\Term('status', 'active'),
			boost: 1.2,
		);

		$array = $cs->toArray();

		\Tester\Assert::same(1.2, $array['constant_score']['boost']);
		\Tester\Assert::same('active', $array['constant_score']['filter']['term']['status']['value']);
	}


	public function testKey(): void
	{
		$cs = new \Spameri\ElasticQuery\Query\ConstantScore(
			new \Spameri\ElasticQuery\Query\Term('status', 'active'),
		);

		\Tester\Assert::same('constant_score_term_status_active', $cs->key());
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

(new ConstantScore())->run();
