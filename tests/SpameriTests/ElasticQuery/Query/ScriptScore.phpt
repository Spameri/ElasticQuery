<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class ScriptScore extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_script_score';


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
		$ss = new \Spameri\ElasticQuery\Query\ScriptScore(
			query: new \Spameri\ElasticQuery\Query\MatchAll(),
			source: "doc['my_field'].value * 2",
			minScore: 0.5,
		);

		$array = $ss->toArray();

		\Tester\Assert::same("doc['my_field'].value * 2", $array['script_score']['script']['source']);
		\Tester\Assert::same(0.5, $array['script_score']['min_score']);
	}


	public function testKey(): void
	{
		$ss = new \Spameri\ElasticQuery\Query\ScriptScore(
			query: new \Spameri\ElasticQuery\Query\MatchAll(),
			source: '_score',
		);

		\Tester\Assert::same('script_score_match_all', $ss->key());
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

(new ScriptScore())->run();
