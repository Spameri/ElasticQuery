<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoShape extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_geo_shape';
	private const SHAPE_INDEX = 'spameri_test_query_geo_shape_lookup';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['location' => ['type' => 'geo_shape']]]];
	}


	public function testToArray(): void
	{
		$geoShape = new \Spameri\ElasticQuery\Query\GeoShape(
			field: 'location',
			shape: ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
			relation: 'within',
		);

		$array = $geoShape->toArray();

		\Tester\Assert::same('envelope', $array['geo_shape']['location']['shape']['type']);
		\Tester\Assert::same('within', $array['geo_shape']['location']['relation']);
	}


	public function testToArrayWithIndexedShape(): void
	{
		$geoShape = new \Spameri\ElasticQuery\Query\GeoShape(
			field: 'location',
			indexedShape: new \Spameri\ElasticQuery\Query\IndexedShape(
				id: 'deu',
				index: 'shapes',
				path: 'location',
				routing: 'eu',
			),
			boost: 2.0,
		);

		$array = $geoShape->toArray();

		\Tester\Assert::same('deu', $array['geo_shape']['location']['indexed_shape']['id']);
		\Tester\Assert::same('shapes', $array['geo_shape']['location']['indexed_shape']['index']);
		\Tester\Assert::same('eu', $array['geo_shape']['location']['indexed_shape']['routing']);
		\Tester\Assert::same(2.0, $array['geo_shape']['boost']);
	}


	public function testRelationValidated(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\GeoShape('loc', ['type' => 'point', 'coordinates' => [0, 0]], 'nonsense');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testRequiresShapeOrIndexedShape(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\GeoShape('loc');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['location' => ['type' => 'point', 'coordinates' => [13.5, 52.5]]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\GeoShape(
						field: 'location',
						shape: ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
						relation: 'within',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithIndexedShape(): void
	{
		$this->request('PUT', self::SHAPE_INDEX, ['mappings' => ['properties' => ['location' => ['type' => 'geo_shape']]]]);
		$this->request(
			'PUT',
			self::SHAPE_INDEX . '/_doc/deu?refresh=true',
			['location' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]]],
		);
		$this->indexDocument(['location' => ['type' => 'point', 'coordinates' => [13.5, 52.5]]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\GeoShape(
						field: 'location',
						relation: 'within',
						indexedShape: new \Spameri\ElasticQuery\Query\IndexedShape(
							id: 'deu',
							index: self::SHAPE_INDEX,
							path: 'location',
						),
					),
				),
			),
		);

		$result = $this->search($query);

		$this->request('DELETE', self::SHAPE_INDEX);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new GeoShape())->run();
