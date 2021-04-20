<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response;


require_once __DIR__ . '/../../bootstrap.php';


class Result extends \Tester\TestCase
{

	public function testCreate() : void
	{
		$result = [
			'took' => 37,
			'timed_out' => FALSE,
			'_shards' => [
				'total' => 5,
				'successful' => 5,
				'skipped' => 0,
				'failed' => 0,
			],
			'hits' => [
				'total' => 47,
				'max_score' => 1.0,
				'hits' => [
					0 => [
						'_index' => 'spameri_guessed-2018-11-29_21-25-34',
						'_type' => 'spameri_guessed',
						'_id' => 'EWhVcWcBhsjlL-GzEP8i',
						'_score' => 1.0,
						'_source' => [
							'guess' => '7kASSmUBq9pZLj7-1Uv4',
							'user' => 'xyC3UmcBeqWWbOzi4uKU',
							'guessed' => 'asdf',
							'when' => '2018-12-02T23:50:58',
							'success' => FALSE,
							'rank' => 0,
						],
					],
					1 => [
						'_index' => 'spameri_guessed-2018-11-29_21-25-34',
						'_type' => 'spameri_guessed',
						'_id' => 'yQ8Bi2cBx3V83AUUwx3m',
						'_score' => 1.0,
						'_source' => [
							'guess' => '7kASSmUBq9pZLj7-1Uv4',
							'user' => 'xyC3UmcBeqWWbOzi4uKU',
							'guessed' => 'Sick Note',
							'when' => '2018-12-07T23:30:07',
							'success' => TRUE,
							'rank' => 19,
						],
					],
				],
			],
			'aggregations' => [
				'guessedRight' => [
					'doc_count_error_upper_bound' => 0,
					'sum_other_doc_count' => 0,
					'buckets' => [
						0 => [
							'key' => '7kASSmUBq9pZLj7-1Uv4',
							'doc_count' => 36,
						],
						1 => [
							'key' => 'es5bKWUBAGn1lK-3fivT',
							'doc_count' => 11,
						],
					],
				],
			],
	  	];

		$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
		/** @var \Spameri\ElasticQuery\Response\ResultSearch $resultObject */
		$resultObject = $resultMapper->map($result);

		\Tester\Assert::true($resultObject instanceof \Spameri\ElasticQuery\Response\ResultSearch);

		// HIT tests
		$hit = $resultObject->getHit('EWhVcWcBhsjlL-GzEP8i');
		\Tester\Assert::same('EWhVcWcBhsjlL-GzEP8i', $hit->id());
		\Tester\Assert::type('array', $hit->source());
		\Tester\Assert::same('spameri_guessed-2018-11-29_21-25-34', $hit->index());
		\Tester\Assert::same(0, $hit->position());
		\Tester\Assert::same(1.0, $hit->score());
		\Tester\Assert::same('spameri_guessed', $hit->type());
		\Tester\Assert::same('xyC3UmcBeqWWbOzi4uKU', $hit->getValue('user'));

		// AGGREGATION tests
		$aggregation = $resultObject->getAggregation('guessedRight');
		\Tester\Assert::same(0, $aggregation->position());
		\Tester\Assert::same('guessedRight', $aggregation->name());
		/** @var \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket $bucket */
		foreach ($aggregation->buckets() as $bucket) {
			if ($bucket->position() === 0) {
				\Tester\Assert::same(0, $bucket->position());
				\Tester\Assert::same('7kASSmUBq9pZLj7-1Uv4', $bucket->key());
				\Tester\Assert::same(36, $bucket->docCount());
			}
		}

		// STATS tests
		\Tester\Assert::same(FALSE, $resultObject->stats()->timedOut());
		\Tester\Assert::same(37, $resultObject->stats()->took());
		\Tester\Assert::same(47, $resultObject->stats()->total());

		// SHARDS tests
		\Tester\Assert::same(5, $resultObject->shards()->total());
		\Tester\Assert::same(5, $resultObject->shards()->successful());
		\Tester\Assert::same(0, $resultObject->shards()->failed());
		\Tester\Assert::same(0, $resultObject->shards()->skipped());
	}

}

(new Result())->run();
