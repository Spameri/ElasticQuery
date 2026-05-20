<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Prefix extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_prefix';


	public function testToArray(): void
	{
		$prefix = new \Spameri\ElasticQuery\Query\Prefix('user', 'ki');

		\Tester\Assert::same('ki', $prefix->toArray()['prefix']['user']['value']);
	}


	public function testCaseInsensitive(): void
	{
		$prefix = new \Spameri\ElasticQuery\Query\Prefix(
			field: 'user',
			query: 'ki',
			caseInsensitive: true,
			rewrite: 'constant_score',
		);

		\Tester\Assert::true($prefix->toArray()['prefix']['user']['case_insensitive']);
		\Tester\Assert::same('constant_score', $prefix->toArray()['prefix']['user']['rewrite']);
	}


	public function testKey(): void
	{
		$prefix = new \Spameri\ElasticQuery\Query\Prefix('user', 'ki');
		\Tester\Assert::same('prefix_user_ki', $prefix->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['user' => 'kimchy']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Prefix('user', 'ki'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['user' => 'kimchy']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Prefix(
						field: 'user',
						query: 'ki',
						boost: 2.0,
						caseInsensitive: false,
						rewrite: 'constant_score',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Prefix())->run();
