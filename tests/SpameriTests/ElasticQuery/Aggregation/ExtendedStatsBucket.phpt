<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class ExtendedStatsBucket extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_extended_stats_bucket';


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\ExtendedStatsBucket(
			bucketsPath: 'sales_per_month>sales',
			sigma: 2.0,
			gapPolicy: 'skip',
			format: '0.00',
		);

		$array = $agg->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['extended_stats_bucket']['buckets_path']);
		\Tester\Assert::same(2.0, $array['extended_stats_bucket']['sigma']);
		\Tester\Assert::same('skip', $array['extended_stats_bucket']['gap_policy']);
	}

}

(new ExtendedStatsBucket())->run();
