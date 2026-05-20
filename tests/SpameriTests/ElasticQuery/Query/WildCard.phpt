<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class WildCard extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_wildcard';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['name' => ['type' => 'keyword']]]];
	}


	public function testToArray(): void
	{
		$wildCard = new \Spameri\ElasticQuery\Query\WildCard('name', 'Aveng*', 1.0);

		$array = $wildCard->toArray();

		\Tester\Assert::same('Aveng*', $array['wildcard']['name']['value']);
		\Tester\Assert::same(1.0, $array['wildcard']['name']['boost']);
	}


	public function testToArrayWithCaseInsensitive(): void
	{
		$wildCard = new \Spameri\ElasticQuery\Query\WildCard(
			field: 'name',
			query: 'aveng*',
			caseInsensitive: true,
			rewrite: 'constant_score',
		);

		\Tester\Assert::true($wildCard->toArray()['wildcard']['name']['case_insensitive']);
		\Tester\Assert::same('constant_score', $wildCard->toArray()['wildcard']['name']['rewrite']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\WildCard('name', 'Aveng*'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithCaseInsensitive(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\WildCard(
						field: 'name',
						query: 'aveng*',
						caseInsensitive: true,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new WildCard())->run();
