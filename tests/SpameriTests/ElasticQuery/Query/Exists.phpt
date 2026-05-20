<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Exists extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_exists';


	public function testToArray(): void
	{
		$exists = new \Spameri\ElasticQuery\Query\Exists('user', 2.0);

		$array = $exists->toArray();

		\Tester\Assert::same('user', $array['exists']['field']);
		\Tester\Assert::same(2.0, $array['exists']['boost']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'foo']);
		$this->indexDocument(['other' => 'bar']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Exists('title'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Exists())->run();
