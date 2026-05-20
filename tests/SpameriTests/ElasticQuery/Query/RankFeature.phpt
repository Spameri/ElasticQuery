<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class RankFeature extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_rank_feature';


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
		$rf = new \Spameri\ElasticQuery\Query\RankFeature(
			field: 'pagerank',
			function: ['saturation' => ['pivot' => 8]],
		);

		$array = $rf->toArray();

		\Tester\Assert::same('pagerank', $array['rank_feature']['field']);
		\Tester\Assert::same(8, $array['rank_feature']['saturation']['pivot']);
	}


	public function testKey(): void
	{
		$rf = new \Spameri\ElasticQuery\Query\RankFeature('pagerank');

		\Tester\Assert::same('rank_feature_pagerank', $rf->key());
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

(new RankFeature())->run();
