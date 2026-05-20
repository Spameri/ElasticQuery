<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class QueryString extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_query_string';


	public function testToArray(): void
	{
		$qs = new \Spameri\ElasticQuery\Query\QueryString(
			query: '(new york city) OR (big apple)',
			defaultField: 'content',
		);

		$array = $qs->toArray();

		\Tester\Assert::same('(new york city) OR (big apple)', $array['query_string']['query']);
		\Tester\Assert::same('content', $array['query_string']['default_field']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$qs = new \Spameri\ElasticQuery\Query\QueryString(
			query: 'foo',
			fields: ['title^2', 'body'],
			defaultOperator: 'AND',
			analyzer: 'standard',
			allowLeadingWildcard: true,
			analyzeWildcard: true,
			autoGenerateSynonymsPhraseQuery: false,
			enablePositionIncrements: true,
			fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
			fuzzyMaxExpansions: 50,
			fuzzyPrefixLength: 0,
			fuzzyTranspositions: true,
			lenient: true,
			maxDeterminizedStates: 10000,
			minimumShouldMatch: '50%',
			quoteAnalyzer: 'standard',
			phraseSlop: 0,
			quoteFieldSuffix: '.exact',
			rewrite: 'constant_score',
			timeZone: 'UTC',
			type: 'best_fields',
			tieBreaker: 0.3,
		);

		$array = $qs->toArray();

		\Tester\Assert::true($array['query_string']['analyze_wildcard']);
		\Tester\Assert::false($array['query_string']['auto_generate_synonyms_phrase_query']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO, $array['query_string']['fuzziness']);
		\Tester\Assert::same(50, $array['query_string']['fuzzy_max_expansions']);
		\Tester\Assert::same('best_fields', $array['query_string']['type']);
		\Tester\Assert::same(0.3, $array['query_string']['tie_breaker']);
	}


	public function testKey(): void
	{
		$qs = new \Spameri\ElasticQuery\Query\QueryString('foo');
		\Tester\Assert::same('query_string_foo', $qs->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['content' => 'hello world']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\QueryString(query: 'hello', defaultField: 'content'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['content' => 'hello world']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\QueryString(
						query: 'hello*',
						defaultField: 'content',
						defaultOperator: 'AND',
						analyzer: 'standard',
						allowLeadingWildcard: false,
						boost: 1.0,
						analyzeWildcard: true,
						autoGenerateSynonymsPhraseQuery: true,
						enablePositionIncrements: true,
						fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness(\Spameri\ElasticQuery\Query\Match\Fuzziness::AUTO),
						fuzzyMaxExpansions: 50,
						fuzzyPrefixLength: 0,
						fuzzyTranspositions: true,
						lenient: true,
						maxDeterminizedStates: 10000,
						phraseSlop: 0,
						type: 'best_fields',
						tieBreaker: 0.3,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new QueryString())->run();
