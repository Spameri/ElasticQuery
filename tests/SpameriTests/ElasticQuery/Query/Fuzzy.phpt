<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Fuzzy extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_fuzzy';


	public function testToArray(): void
	{
		$fuzzy = new \Spameri\ElasticQuery\Query\Fuzzy('name', 'Avengers', 1.0, 2, 0, 100);

		$array = $fuzzy->toArray();

		\Tester\Assert::same('Avengers', $array['fuzzy']['name']['value']);
		\Tester\Assert::same(1.0, $array['fuzzy']['name']['boost']);
		\Tester\Assert::same(2, $array['fuzzy']['name']['fuzziness']);
		\Tester\Assert::same(0, $array['fuzzy']['name']['prefix_length']);
		\Tester\Assert::same(100, $array['fuzzy']['name']['max_expansions']);
	}


	public function testToArrayWithTranspositionsAndRewrite(): void
	{
		$fuzzy = new \Spameri\ElasticQuery\Query\Fuzzy(
			field: 'name',
			query: 'Avengers',
			transpositions: false,
			rewrite: 'constant_score',
		);

		$array = $fuzzy->toArray();

		\Tester\Assert::false($array['fuzzy']['name']['transpositions']);
		\Tester\Assert::same('constant_score', $array['fuzzy']['name']['rewrite']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Fuzzy('name', 'Avengars'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Fuzzy(
						field: 'name',
						query: 'Avengars',
						boost: 1.0,
						fuzziness: 2,
						prefixLength: 0,
						maxExpansion: 50,
						transpositions: true,
						rewrite: 'constant_score',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Fuzzy())->run();
