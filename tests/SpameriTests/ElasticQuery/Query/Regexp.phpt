<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Regexp extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_regexp';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['user' => ['type' => 'keyword']]]];
	}


	public function testToArray(): void
	{
		$regexp = new \Spameri\ElasticQuery\Query\Regexp('user', 'k.*y');

		\Tester\Assert::same('k.*y', $regexp->toArray()['regexp']['user']['value']);
	}


	public function testRewriteOption(): void
	{
		$regexp = new \Spameri\ElasticQuery\Query\Regexp(
			field: 'user',
			query: 'k.*',
			rewrite: 'constant_score',
		);

		\Tester\Assert::same('constant_score', $regexp->toArray()['regexp']['user']['rewrite']);
	}


	public function testKey(): void
	{
		$regexp = new \Spameri\ElasticQuery\Query\Regexp('user', 'k.*');
		\Tester\Assert::same('regexp_user_k.*', $regexp->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['user' => 'kimchy']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Regexp('user', 'k.*'),
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
					new \Spameri\ElasticQuery\Query\Regexp(
						field: 'user',
						query: 'k.*',
						boost: 2.0,
						flags: 'ALL',
						caseInsensitive: false,
						maxDeterminizedStates: 10000,
						rewrite: 'constant_score',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Regexp())->run();
