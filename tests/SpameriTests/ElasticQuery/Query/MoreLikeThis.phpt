<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MoreLikeThis extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_mlt';


	public function testToArray(): void
	{
		$mlt = new \Spameri\ElasticQuery\Query\MoreLikeThis(
			fields: ['title', 'body'],
			like: ['quick brown fox', ['_index' => 'imdb', '_id' => '1']],
			minTermFreq: 1,
			maxQueryTerms: 12,
		);

		$array = $mlt->toArray();

		\Tester\Assert::same(['title', 'body'], $array['more_like_this']['fields']);
		\Tester\Assert::same(1, $array['more_like_this']['min_term_freq']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$mlt = new \Spameri\ElasticQuery\Query\MoreLikeThis(
			fields: ['title'],
			like: ['quick fox'],
			boostTerms: 0.5,
			include: true,
			minDocFreq: 5,
			maxDocFreq: 1000,
			minWordLength: 2,
			maxWordLength: 100,
			stopWords: ['the', 'a'],
			analyzer: 'standard',
			boost: 2.0,
			failOnUnsupportedField: false,
		);

		$array = $mlt->toArray();

		\Tester\Assert::same(0.5, $array['more_like_this']['boost_terms']);
		\Tester\Assert::true($array['more_like_this']['include']);
		\Tester\Assert::same(5, $array['more_like_this']['min_doc_freq']);
		\Tester\Assert::same(1000, $array['more_like_this']['max_doc_freq']);
		\Tester\Assert::same(['the', 'a'], $array['more_like_this']['stop_words']);
		\Tester\Assert::same(2.0, $array['more_like_this']['boost']);
		\Tester\Assert::false($array['more_like_this']['fail_on_unsupported_field']);
	}


	public function testRequiresFields(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\MoreLikeThis([], ['foo']);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testRequiresLike(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\MoreLikeThis(['f'], []);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'quick brown fox', 'body' => 'jumps over the lazy dog']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\MoreLikeThis(
						fields: ['title', 'body'],
						like: ['quick brown fox'],
						minTermFreq: 1,
						minDocFreq: 1,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new MoreLikeThis())->run();
