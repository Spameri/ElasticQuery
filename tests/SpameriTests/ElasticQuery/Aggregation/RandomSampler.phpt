<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class RandomSampler extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_random_sampler';


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\RandomSampler(
			probability: 0.1,
			seed: 42,
		);

		$array = $agg->toArray();

		\Tester\Assert::same(0.1, $array['random_sampler']['probability']);
		\Tester\Assert::same(42, $array['random_sampler']['seed']);
	}

}

(new RandomSampler())->run();
