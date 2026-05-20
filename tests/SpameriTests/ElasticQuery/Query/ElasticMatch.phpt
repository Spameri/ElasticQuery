<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class ElasticMatch extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_match';


	public function testToArray(): void
	{
		$match = new \Spameri\ElasticQuery\Query\ElasticMatch(
			'name',
			'Avengers',
			1.0,
			new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
			2,
			\Spameri\ElasticQuery\Query\Match\Operator::OR,
			'standard',
		);

		$array = $match->toArray();

		\Tester\Assert::same('Avengers', $array['match']['name']['query']);
		\Tester\Assert::same(1.0, $array['match']['name']['boost']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Operator::OR, $array['match']['name']['operator']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO, $array['match']['name']['fuzziness']);
		\Tester\Assert::same('standard', $array['match']['name']['analyzer']);
		\Tester\Assert::same(2, $array['match']['name']['minimum_should_match']);
	}


	public function testToArrayWithAllNewOptions(): void
	{
		$match = new \Spameri\ElasticQuery\Query\ElasticMatch(
			field: 'name',
			query: 'Avengers',
			zeroTermsQuery: 'all',
			autoGenerateSynonymsPhraseQuery: false,
			lenient: true,
			prefixLength: 1,
			maxExpansions: 50,
			fuzzyTranspositions: false,
			fuzzyRewrite: 'constant_score',
		);

		$array = $match->toArray();

		\Tester\Assert::same('all', $array['match']['name']['zero_terms_query']);
		\Tester\Assert::false($array['match']['name']['auto_generate_synonyms_phrase_query']);
		\Tester\Assert::true($array['match']['name']['lenient']);
		\Tester\Assert::same(1, $array['match']['name']['prefix_length']);
		\Tester\Assert::same(50, $array['match']['name']['max_expansions']);
		\Tester\Assert::false($array['match']['name']['fuzzy_transpositions']);
		\Tester\Assert::same('constant_score', $array['match']['name']['fuzzy_rewrite']);
	}


	public function testMinimumShouldMatchString(): void
	{
		$match = new \Spameri\ElasticQuery\Query\ElasticMatch('name', 'Avengers Endgame', 1.0, null, '75%');
		\Tester\Assert::same('75%', $match->toArray()['match']['name']['minimum_should_match']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers Endgame']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\ElasticMatch('name', 'Avengers'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['name' => 'Avengers Endgame Infinity']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\ElasticMatch(
						field: 'name',
						query: 'Avengers',
						boost: 2.0,
						fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
						operator: \Spameri\ElasticQuery\Query\Match\Operator::OR,
						analyzer: 'standard',
						zeroTermsQuery: 'none',
						autoGenerateSynonymsPhraseQuery: true,
						lenient: true,
						prefixLength: 0,
						maxExpansions: 50,
						fuzzyTranspositions: true,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new ElasticMatch())->run();
