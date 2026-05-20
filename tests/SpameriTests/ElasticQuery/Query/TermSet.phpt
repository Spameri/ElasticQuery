<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class TermSet extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_term_set';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'programming_languages' => ['type' => 'keyword'],
					'required_matches' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$termSet = new \Spameri\ElasticQuery\Query\TermSet(
			field: 'programming_languages',
			terms: ['c++', 'java', 'php'],
			minimumShouldMatchField: 'required_matches',
			boost: 2.0,
		);

		$array = $termSet->toArray();

		\Tester\Assert::same(
			['c++', 'java', 'php'],
			$array['terms_set']['programming_languages']['terms'],
		);
		\Tester\Assert::same(2.0, $array['terms_set']['programming_languages']['boost']);
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


	public function testCreate(): void
	{
		$this->indexDocument([
			'programming_languages' => ['c++', 'java', 'php'],
			'required_matches' => 2,
		]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\TermSet(
						field: 'programming_languages',
						terms: ['c++', 'java', 'php'],
						minimumShouldMatchField: 'required_matches',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new TermSet())->run();
