<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class PhrasePrefix extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_phrase_prefix';


	public function testToArrayBasic(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			'title',
			'quick brown f',
		);

		$array = $phrasePrefix->toArray();

		\Tester\Assert::same('quick brown f', $array['match_phrase_prefix']['title']['query']);
		\Tester\Assert::same(1.0, $array['match_phrase_prefix']['title']['boost']);
		\Tester\Assert::same(1, $array['match_phrase_prefix']['title']['slop']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			field: 'description',
			queryString: 'search phrase',
			boost: 2.0,
			slop: 3,
			analyzer: 'standard',
			maxExpansions: 50,
			zeroTermsQuery: 'none',
		);

		$array = $phrasePrefix->toArray();

		\Tester\Assert::same(2.0, $array['match_phrase_prefix']['description']['boost']);
		\Tester\Assert::same(3, $array['match_phrase_prefix']['description']['slop']);
		\Tester\Assert::same('standard', $array['match_phrase_prefix']['description']['analyzer']);
		\Tester\Assert::same(50, $array['match_phrase_prefix']['description']['max_expansions']);
		\Tester\Assert::same('none', $array['match_phrase_prefix']['description']['zero_terms_query']);
	}


	public function testKey(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix('title', 'test query');
		\Tester\Assert::same('phrase_prefix_title_test query', $phrasePrefix->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'Avengers Endgame']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\PhrasePrefix('title', 'Aveng'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['title' => 'Avengers Endgame']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\PhrasePrefix(
						field: 'title',
						queryString: 'Aveng',
						boost: 1.5,
						slop: 1,
						analyzer: 'standard',
						maxExpansions: 50,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new PhrasePrefix())->run();
