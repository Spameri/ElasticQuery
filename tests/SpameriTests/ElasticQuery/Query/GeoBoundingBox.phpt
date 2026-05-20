<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoBoundingBox extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_geo_bounding_box';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['location' => ['type' => 'geo_point']]]];
	}


	public function testToArray(): void
	{
		$gbb = new \Spameri\ElasticQuery\Query\GeoBoundingBox(
			field: 'location',
			topLeftLat: 40.73,
			topLeftLon: -74.1,
			bottomRightLat: 40.01,
			bottomRightLon: -71.12,
		);

		$array = $gbb->toArray();

		\Tester\Assert::same(40.73, $array['geo_bounding_box']['location']['top_left']['lat']);
		\Tester\Assert::same(-71.12, $array['geo_bounding_box']['location']['bottom_right']['lon']);
		\Tester\Assert::same(1.0, $array['geo_bounding_box']['boost']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$gbb = new \Spameri\ElasticQuery\Query\GeoBoundingBox(
			field: 'location',
			topLeftLat: 40.73,
			topLeftLon: -74.1,
			bottomRightLat: 40.01,
			bottomRightLon: -71.12,
			type: 'memory',
			validationMethod: 'COERCE',
			ignoreUnmapped: true,
			boost: 2.0,
		);

		$array = $gbb->toArray();

		\Tester\Assert::same('memory', $array['geo_bounding_box']['type']);
		\Tester\Assert::same('COERCE', $array['geo_bounding_box']['validation_method']);
		\Tester\Assert::true($array['geo_bounding_box']['ignore_unmapped']);
		\Tester\Assert::same(2.0, $array['geo_bounding_box']['boost']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['location' => ['lat' => 40.5, 'lon' => -73.0]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\GeoBoundingBox(
						field: 'location',
						topLeftLat: 41.0,
						topLeftLon: -75.0,
						bottomRightLat: 40.0,
						bottomRightLon: -71.0,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new GeoBoundingBox())->run();
