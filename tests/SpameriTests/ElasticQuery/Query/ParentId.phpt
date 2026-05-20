<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class ParentId extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_parent_id';


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
		$parentId = new \Spameri\ElasticQuery\Query\ParentId(type: 'comment', id: '1');

		$array = $parentId->toArray();

		\Tester\Assert::same('comment', $array['parent_id']['type']);
		\Tester\Assert::same('1', $array['parent_id']['id']);
	}


	public function testKey(): void
	{
		$parentId = new \Spameri\ElasticQuery\Query\ParentId('comment', '1');

		\Tester\Assert::same('parent_id_comment_1', $parentId->key());
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

(new ParentId())->run();
