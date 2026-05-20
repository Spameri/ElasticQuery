<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MatchPhrase extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_match_phrase';


	public function testToArray(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchPhrase('name', 'Avengers', 1.0, 1, 'standard');

		$array = $match->toArray();

		\Tester\Assert::same('Avengers', $array['match_phrase']['name']['query']);
		\Tester\Assert::same(1.0, $array['match_phrase']['name']['boost']);
		\Tester\Assert::same(1, $array['match_phrase']['name']['slop']);
		\Tester\Assert::same('standard', $array['match_phrase']['name']['analyzer']);
	}


	public function testZeroTermsQuery(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchPhrase(
			field: 'name',
			query: 'foo',
			zeroTermsQuery: 'all',
		);

		\Tester\Assert::same('all', $match->toArray()['match_phrase']['name']['zero_terms_query']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers Endgame']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\MatchPhrase('name', 'Avengers Endgame'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['name' => 'Avengers Endgame']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\MatchPhrase(
						field: 'name',
						query: 'Avengers Endgame',
						boost: 1.5,
						slop: 1,
						analyzer: 'standard',
						zeroTermsQuery: 'none',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new MatchPhrase())->run();
