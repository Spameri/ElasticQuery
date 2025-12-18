<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class ResultSearch extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$stats = new \Spameri\ElasticQuery\Response\Stats(10, false, 100);
		$shards = new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0);
		$hitCollection = new \Spameri\ElasticQuery\Response\Result\HitCollection();
		$aggregationCollection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection();

		$result = new \Spameri\ElasticQuery\Response\ResultSearch(
			$stats,
			$shards,
			$hitCollection,
			$aggregationCollection,
		);

		\Tester\Assert::same($stats, $result->stats());
		\Tester\Assert::same($shards, $result->shards());
		\Tester\Assert::same($hitCollection, $result->hits());
		\Tester\Assert::same($aggregationCollection, $result->aggregations());
	}


	public function testGetHit(): void
	{
		$hit1 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test 1'],
			0,
			'test_index',
			'_doc',
			'id_1',
			1.0,
			1,
		);
		$hit2 = new \Spameri\ElasticQuery\Response\Result\Hit(
			['name' => 'Test 2'],
			1,
			'test_index',
			'_doc',
			'id_2',
			0.9,
			1,
		);

		$result = new \Spameri\ElasticQuery\Response\ResultSearch(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 2),
			new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0),
			new \Spameri\ElasticQuery\Response\Result\HitCollection($hit1, $hit2),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
		);

		$foundHit = $result->getHit('id_2');

		\Tester\Assert::same($hit2, $foundHit);
		\Tester\Assert::same('Test 2', $foundHit->getValue('name'));
	}


	public function testGetHitNotFound(): void
	{
		$result = new \Spameri\ElasticQuery\Response\ResultSearch(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 0),
			new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
		);

		\Tester\Assert::exception(
			static function () use ($result): void {
				$result->getHit('nonexistent');
			},
			\Spameri\ElasticQuery\Exception\HitNotFound::class,
		);
	}


	public function testGetAggregation(): void
	{
		$aggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$result = new \Spameri\ElasticQuery\Response\ResultSearch(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 0),
			new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection($aggregation),
		);

		$foundAggregation = $result->getAggregation('categories');

		\Tester\Assert::same($aggregation, $foundAggregation);
	}


	public function testGetAggregationNotFound(): void
	{
		$result = new \Spameri\ElasticQuery\Response\ResultSearch(
			new \Spameri\ElasticQuery\Response\Stats(10, false, 0),
			new \Spameri\ElasticQuery\Response\Shards(5, 5, 0, 0),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
		);

		\Tester\Assert::exception(
			static function () use ($result): void {
				$result->getAggregation('nonexistent');
			},
			\Spameri\ElasticQuery\Exception\AggregationNotFound::class,
		);
	}

}

(new ResultSearch())->run();
