<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Term extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_term';


	public function testToArray(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term(
			'name',
			'Avengers',
			1.0,
		);

		$array = $term->toArray();

		\Tester\Assert::same('Avengers', $array['term']['name']['value']);
		\Tester\Assert::same(1.0, $array['term']['name']['boost']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Term('name.keyword', 'Avengers'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Term())->run();
