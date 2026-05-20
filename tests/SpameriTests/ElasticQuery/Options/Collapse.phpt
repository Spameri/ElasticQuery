<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class Collapse extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_collapse';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'user_id' => ['type' => 'keyword'],
					'created' => ['type' => 'date'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$collapse = new \Spameri\ElasticQuery\Options\Collapse(
			field: 'user_id',
			innerHits: new \Spameri\ElasticQuery\Query\InnerHits(name: 'recent', size: 5),
			maxConcurrentGroupSearches: 4,
		);

		$array = $collapse->toArray();

		\Tester\Assert::same('user_id', $array['field']);
		\Tester\Assert::same('recent', $array['inner_hits']['name']);
		\Tester\Assert::same(4, $array['max_concurrent_group_searches']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['user_id' => 'a', 'created' => '2024-01-01']);
		$this->indexDocument(['user_id' => 'a', 'created' => '2024-06-01']);
		$this->indexDocument(['user_id' => 'b', 'created' => '2024-03-01']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			options: new \Spameri\ElasticQuery\Options(
				collapse: new \Spameri\ElasticQuery\Options\Collapse(field: 'user_id'),
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(3, $this->search($elasticQuery)->stats()->total());
	}

}

(new Collapse())->run();
