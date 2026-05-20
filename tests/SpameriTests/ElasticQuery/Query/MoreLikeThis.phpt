<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MoreLikeThis extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_mlt';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


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


	public function testKey(): void
	{
		$mlt = new \Spameri\ElasticQuery\Query\MoreLikeThis(['title'], ['foo']);

		\Tester\Assert::same('more_like_this_title', $mlt->key());
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}

}

(new MoreLikeThis())->run();
