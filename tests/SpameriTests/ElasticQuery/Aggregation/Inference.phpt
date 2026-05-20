<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Inference extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_inference';


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\Inference(
			modelId: 'my_model',
			bucketsPath: ['feature' => 'avg_value'],
			inferenceConfig: ['regression' => ['results_field' => 'prediction']],
		);

		$array = $agg->toArray();

		\Tester\Assert::same('my_model', $array['inference']['model_id']);
		\Tester\Assert::same(['feature' => 'avg_value'], $array['inference']['buckets_path']);
	}

}

(new Inference())->run();
