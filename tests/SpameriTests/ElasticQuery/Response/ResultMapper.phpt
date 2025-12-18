<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;

require_once __DIR__ . '/../../bootstrap.php';


class ResultMapper extends \Tester\TestCase
{

	public function testMapSearchResults(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'took' => 5,
			'timed_out' => false,
			'_shards' => [
				'total' => 5,
				'successful' => 5,
				'skipped' => 0,
				'failed' => 0,
			],
			'hits' => [
				'total' => ['value' => 2, 'relation' => 'eq'],
				'max_score' => 1.0,
				'hits' => [
					[
						'_index' => 'test_index',
						'_id' => 'id_1',
						'_score' => 1.0,
						'_source' => ['name' => 'Document 1'],
					],
					[
						'_index' => 'test_index',
						'_id' => 'id_2',
						'_score' => 0.9,
						'_source' => ['name' => 'Document 2'],
					],
				],
			],
		];

		$result = $mapper->map($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultSearch::class, $result);
		\Tester\Assert::same(5, $result->stats()->took());
		\Tester\Assert::false($result->stats()->timedOut());
		\Tester\Assert::same(2, $result->stats()->total());
		\Tester\Assert::same(5, $result->shards()->total());
		\Tester\Assert::same(2, $result->hits()->count());
	}


	public function testMapSearchResultsWithLegacyTotal(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'took' => 5,
			'timed_out' => false,
			'_shards' => [
				'total' => 5,
				'successful' => 5,
				'skipped' => 0,
				'failed' => 0,
			],
			'hits' => [
				'total' => 100,
				'max_score' => 1.0,
				'hits' => [],
			],
		];

		$result = $mapper->map($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultSearch::class, $result);
		\Tester\Assert::same(100, $result->stats()->total());
	}


	public function testMapSingleResult(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'_index' => 'test_index',
			'_id' => 'doc_1',
			'version' => 1,
			'found' => true,
			'_source' => ['name' => 'Test Document', 'price' => 100],
		];

		$result = $mapper->map($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultSingle::class, $result);
		\Tester\Assert::true($result->stats()->found());
		\Tester\Assert::same(1, $result->stats()->version());
		\Tester\Assert::same('doc_1', $result->hit()->id());
		\Tester\Assert::same('Test Document', $result->hit()->getValue('name'));
	}


	public function testMapBulkResultDirect(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'took' => 30,
			'errors' => false,
			'timed_out' => false,
			'hits' => [
				'total' => 0,
			],
			'items' => [
				'index' => [
					'_index' => 'test_index',
					'_id' => 'id_1',
					'_version' => 1,
					'result' => 'created',
					'_shards' => [
						'total' => 2,
						'successful' => 1,
						'failed' => 0,
					],
					'status' => 201,
					'_seq_no' => 0,
					'_primary_term' => 1,
				],
			],
		];

		$result = $mapper->mapBulkResult($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultBulk::class, $result);
		\Tester\Assert::same(30, $result->stats()->took());
	}


	public function testMapVersionResult(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'name' => 'test-node',
			'cluster_name' => 'test-cluster',
			'cluster_uuid' => 'abc123',
			'version' => [
				'number' => '8.0.0',
				'build_hash' => 'abc123',
				'build_snapshot' => false,
				'lucene_version' => '9.0.0',
			],
			'tagline' => 'You Know, for Search',
		];

		$result = $mapper->map($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultVersion::class, $result);
		\Tester\Assert::same('test-node', $result->name());
		\Tester\Assert::same('test-cluster', $result->clusterName());
		\Tester\Assert::same('8.0.0', $result->version()->number());
	}


	public function testMapInvalidResponse(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		\Tester\Assert::exception(
			static function () use ($mapper): void {
				$mapper->map(['invalid' => 'response']);
			},
			\Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped::class,
		);
	}


	public function testMapSearchResultsWithAggregations(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'took' => 5,
			'timed_out' => false,
			'_shards' => [
				'total' => 5,
				'successful' => 5,
				'skipped' => 0,
				'failed' => 0,
			],
			'hits' => [
				'total' => ['value' => 0, 'relation' => 'eq'],
				'max_score' => null,
				'hits' => [],
			],
			'aggregations' => [
				'categories' => [
					'buckets' => [
						['key' => 'electronics', 'doc_count' => 100],
						['key' => 'books', 'doc_count' => 50],
					],
				],
			],
		];

		$result = $mapper->map($elasticResponse);

		\Tester\Assert::type(\Spameri\ElasticQuery\Response\ResultSearch::class, $result);
		$aggregation = $result->getAggregation('categories');
		\Tester\Assert::same('categories', $aggregation->name());
	}


	public function testMapHits(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'hits' => [
				'hits' => [
					[
						'_index' => 'test_index',
						'_id' => 'id_1',
						'_score' => 1.5,
						'_source' => ['name' => 'Test'],
					],
				],
			],
		];

		$hitCollection = $mapper->mapHits($elasticResponse);

		\Tester\Assert::same(1, $hitCollection->count());
		\Tester\Assert::same(['id_1'], $hitCollection->ids());
	}


	public function testMapShards(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'_shards' => [
				'total' => 5,
				'successful' => 4,
				'skipped' => 1,
				'failed' => 0,
			],
		];

		$shards = $mapper->mapShards($elasticResponse);

		\Tester\Assert::same(5, $shards->total());
		\Tester\Assert::same(4, $shards->successful());
		\Tester\Assert::same(1, $shards->skipped());
		\Tester\Assert::same(0, $shards->failed());
	}


	public function testMapStats(): void
	{
		$mapper = new \Spameri\ElasticQuery\Response\ResultMapper();

		$elasticResponse = [
			'took' => 15,
			'timed_out' => true,
			'hits' => [
				'total' => ['value' => 500, 'relation' => 'eq'],
			],
		];

		$stats = $mapper->mapStats($elasticResponse);

		\Tester\Assert::same(15, $stats->took());
		\Tester\Assert::true($stats->timedOut());
		\Tester\Assert::same(500, $stats->total());
	}

}

(new ResultMapper())->run();
