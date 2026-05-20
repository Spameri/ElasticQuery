<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class HasChild extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_has_child';


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
		$hasChild = new \Spameri\ElasticQuery\Query\HasChild(
			type: 'comment',
			query: new \Spameri\ElasticQuery\Query\Term('author', 'john'),
			scoreMode: 'max',
			minChildren: 1,
		);

		$array = $hasChild->toArray();

		\Tester\Assert::same('comment', $array['has_child']['type']);
		\Tester\Assert::same('max', $array['has_child']['score_mode']);
		\Tester\Assert::same(1, $array['has_child']['min_children']);
	}


	public function testKey(): void
	{
		$hasChild = new \Spameri\ElasticQuery\Query\HasChild(
			'comment',
			new \Spameri\ElasticQuery\Query\Term('author', 'john'),
		);

		\Tester\Assert::same('has_child_comment', $hasChild->key());
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

(new HasChild())->run();
