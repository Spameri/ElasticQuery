<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class FrequentItemSets extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_frequent_item_sets';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'category' => ['type' => 'keyword'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\FrequentItemSets(
			fields: [['field' => 'category']],
			minimumSupport: 0.1,
			minimumSetSize: 2,
			size: 10,
		);

		$array = $agg->toArray();

		\Tester\Assert::same(0.1, $array['frequent_item_sets']['minimum_support']);
		\Tester\Assert::same(2, $array['frequent_item_sets']['minimum_set_size']);
	}


	public function testRequiresFields(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\FrequentItemSets([]);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}

}

(new FrequentItemSets())->run();
