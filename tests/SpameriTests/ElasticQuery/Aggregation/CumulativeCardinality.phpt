<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class CumulativeCardinality extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_cumulative_cardinality';


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\CumulativeCardinality(
			bucketsPath: 'distinct',
			format: '0.00',
		);

		$array = $agg->toArray();

		\Tester\Assert::same('distinct', $array['cumulative_cardinality']['buckets_path']);
		\Tester\Assert::same('0.00', $array['cumulative_cardinality']['format']);
	}

}

(new CumulativeCardinality())->run();
