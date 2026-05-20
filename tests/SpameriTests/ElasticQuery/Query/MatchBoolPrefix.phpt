<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MatchBoolPrefix extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_match_bool_prefix';


	public function testToArray(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix(
			field: 'message',
			query: 'quick brown f',
		);

		\Tester\Assert::same('quick brown f', $match->toArray()['match_bool_prefix']['message']['query']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix(
			field: 'message',
			query: 'q',
			boost: 1.5,
			operator: 'or',
			minimumShouldMatch: '50%',
			analyzer: 'standard',
			fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
			prefixLength: 0,
			maxExpansions: 50,
			fuzzyTranspositions: true,
			fuzzyRewrite: 'constant_score',
		);

		$array = $match->toArray();

		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO, $array['match_bool_prefix']['message']['fuzziness']);
		\Tester\Assert::same(0, $array['match_bool_prefix']['message']['prefix_length']);
		\Tester\Assert::same(50, $array['match_bool_prefix']['message']['max_expansions']);
		\Tester\Assert::true($array['match_bool_prefix']['message']['fuzzy_transpositions']);
		\Tester\Assert::same('constant_score', $array['match_bool_prefix']['message']['fuzzy_rewrite']);
	}


	public function testKey(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix('message', 'quick');
		\Tester\Assert::same('match_bool_prefix_message_quick', $match->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['message' => 'quick brown fox']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\MatchBoolPrefix('message', 'quick brown f'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['message' => 'quick brown fox']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\MatchBoolPrefix(
						field: 'message',
						query: 'qiuck',
						fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
						prefixLength: 0,
						maxExpansions: 50,
						fuzzyTranspositions: true,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::true($result->stats()->total() >= 0);
	}

}

(new MatchBoolPrefix())->run();
