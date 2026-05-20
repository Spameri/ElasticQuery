<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class GeoLine extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_geo_line';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'location' => ['type' => 'geo_point'],
					'ts' => ['type' => 'date'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\GeoLine(
			pointField: 'location',
			sortField: 'ts',
			sortOrder: 'desc',
			includeSort: true,
			size: 100,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('location', $array['geo_line']['point']['field']);
		\Tester\Assert::same('ts', $array['geo_line']['sort']['field']);
		\Tester\Assert::same('desc', $array['geo_line']['sort_order']);
		\Tester\Assert::true($array['geo_line']['include_sort']);
		\Tester\Assert::same(100, $array['geo_line']['size']);
	}


	public function testCreate(): void
	{
		// geo_line requires gold-tier license; skip on basic
		$this->indexDocument(['location' => ['lat' => 50, 'lon' => 14], 'ts' => '2024-01-01']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'path', null, new \Spameri\ElasticQuery\Aggregation\GeoLine('location', 'ts'),
		));

		try {
			$result = $this->search($elasticQuery);
			\Tester\Assert::same(1, $result->stats()->total());
		} catch (\Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped $e) {
			if (\str_contains($e->getMessage(), 'license')) {
				\Tester\Environment::skip('geo_line aggregation requires gold-tier license');
			}
			throw $e;
		}
	}

}

(new GeoLine())->run();
