<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class HasParent extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_has_parent';


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
		$hasParent = new \Spameri\ElasticQuery\Query\HasParent(
			parentType: 'blog',
			query: new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
			score: true,
		);

		$array = $hasParent->toArray();

		\Tester\Assert::same('blog', $array['has_parent']['parent_type']);
		\Tester\Assert::true($array['has_parent']['score']);
	}


	public function testKey(): void
	{
		$hasParent = new \Spameri\ElasticQuery\Query\HasParent(
			'blog',
			new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
		);

		\Tester\Assert::same('has_parent_blog', $hasParent->key());
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

(new HasParent())->run();
