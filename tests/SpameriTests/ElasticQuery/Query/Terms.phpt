<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Terms extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_terms';
	private const LOOKUP_INDEX = 'spameri_test_query_terms_lookup';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'name' => ['type' => 'keyword'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$terms = new \Spameri\ElasticQuery\Query\Terms('name', ['Avengers'], 1.0);

		$array = $terms->toArray();

		\Tester\Assert::same('Avengers', $array['terms']['name'][0]);
		\Tester\Assert::same(1.0, $array['terms']['boost']);
	}


	public function testToArrayWithLookup(): void
	{
		$lookup = new \Spameri\ElasticQuery\Query\TermsLookup(
			index: 'users',
			id: '42',
			path: 'friends',
		);
		$terms = new \Spameri\ElasticQuery\Query\Terms('user_id', $lookup);

		$array = $terms->toArray();

		\Tester\Assert::same('users', $array['terms']['user_id']['index']);
		\Tester\Assert::same('42', $array['terms']['user_id']['id']);
		\Tester\Assert::same('friends', $array['terms']['user_id']['path']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['name' => 'Avengers']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Terms('name', ['Avengers']),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithLookup(): void
	{
		$this->request('PUT', self::LOOKUP_INDEX, ['mappings' => ['properties' => ['ids' => ['type' => 'keyword']]]]);
		$this->request('PUT', self::LOOKUP_INDEX . '/_doc/list?refresh=true', ['ids' => ['Avengers', 'Endgame']]);

		$this->indexDocument(['name' => 'Avengers']);
		$this->indexDocument(['name' => 'Other']);

		$lookup = new \Spameri\ElasticQuery\Query\TermsLookup(
			index: self::LOOKUP_INDEX,
			id: 'list',
			path: 'ids',
		);
		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Terms('name', $lookup),
				),
			),
		);

		$result = $this->search($query);

		$this->request('DELETE', self::LOOKUP_INDEX);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Terms())->run();
