<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class TermSet extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_term_set';


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
		$termSet = new \Spameri\ElasticQuery\Query\TermSet(
			field: 'programming_languages',
			terms: ['c++', 'java', 'php'],
			minimumShouldMatchField: 'required_matches',
		);

		$array = $termSet->toArray();

		\Tester\Assert::same(
			['c++', 'java', 'php'],
			$array['terms_set']['programming_languages']['terms'],
		);
		\Tester\Assert::same(
			'required_matches',
			$array['terms_set']['programming_languages']['minimum_should_match_field'],
		);
	}


	public function testRequiresTerms(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\TermSet('f', [], 'm');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testRequiresMinimumShouldMatch(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\TermSet('f', ['a']);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$termSet = new \Spameri\ElasticQuery\Query\TermSet(
			'tags',
			['php', 'es'],
			minimumShouldMatchField: 'min',
		);

		\Tester\Assert::same('terms_set_tags_php-es', $termSet->key());
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

(new TermSet())->run();
