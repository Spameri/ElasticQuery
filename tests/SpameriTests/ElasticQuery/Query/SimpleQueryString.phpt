<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SimpleQueryString extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_simple_query_string';


	public function testToArray(): void
	{
		$sqs = new \Spameri\ElasticQuery\Query\SimpleQueryString(
			query: 'foo + bar -baz',
			fields: ['title^2', 'body'],
		);

		$array = $sqs->toArray();

		\Tester\Assert::same('foo + bar -baz', $array['simple_query_string']['query']);
		\Tester\Assert::same(['title^2', 'body'], $array['simple_query_string']['fields']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$sqs = new \Spameri\ElasticQuery\Query\SimpleQueryString(
			query: 'foo',
			fields: ['body'],
			defaultOperator: 'AND',
			analyzer: 'standard',
			flags: 'ALL',
			analyzeWildcard: true,
			autoGenerateSynonymsPhraseQuery: false,
			fuzzyMaxExpansions: 50,
			fuzzyPrefixLength: 0,
			fuzzyTranspositions: true,
			lenient: true,
			minimumShouldMatch: '50%',
			quoteFieldSuffix: '.exact',
		);

		$array = $sqs->toArray();

		\Tester\Assert::true($array['simple_query_string']['analyze_wildcard']);
		\Tester\Assert::false($array['simple_query_string']['auto_generate_synonyms_phrase_query']);
		\Tester\Assert::same(50, $array['simple_query_string']['fuzzy_max_expansions']);
		\Tester\Assert::same('.exact', $array['simple_query_string']['quote_field_suffix']);
	}


	public function testKey(): void
	{
		$sqs = new \Spameri\ElasticQuery\Query\SimpleQueryString('q');
		\Tester\Assert::same('simple_query_string_q', $sqs->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['body' => 'hello world']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\SimpleQueryString(query: 'hello', fields: ['body']),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['body' => 'hello world']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\SimpleQueryString(
						query: 'hello',
						fields: ['body'],
						defaultOperator: 'AND',
						analyzer: 'standard',
						flags: 'ALL',
						analyzeWildcard: true,
						autoGenerateSynonymsPhraseQuery: true,
						fuzzyMaxExpansions: 50,
						fuzzyPrefixLength: 0,
						fuzzyTranspositions: true,
						lenient: true,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new SimpleQueryString())->run();
