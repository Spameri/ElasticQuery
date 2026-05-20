<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class TimeSeries extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_time_series';


	public function testToArrayEmpty(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\TimeSeries();

		$array = $agg->toArray();

		\Tester\Assert::type(\stdClass::class, $array['time_series']);
	}


	public function testToArrayWithOptions(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\TimeSeries(
			keyed: true,
			size: 100,
		);

		$array = $agg->toArray();

		\Tester\Assert::true($array['time_series']['keyed']);
		\Tester\Assert::same(100, $array['time_series']['size']);
	}

}

(new TimeSeries())->run();
