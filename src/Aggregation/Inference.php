<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * Inference pipeline aggregation — applies a trained ML model.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-inference-bucket-aggregation.html
 */
class Inference implements LeafAggregationInterface
{

	/**
	 * @param array<string, string> $bucketsPath Map of input feature => agg path.
	 * @param array<string, mixed>|null $inferenceConfig
	 */
	public function __construct(
		private string $modelId,
		private array $bucketsPath,
		private array|null $inferenceConfig = null,
		private string $key = 'inference',
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'model_id' => $this->modelId,
			'buckets_path' => $this->bucketsPath,
		];

		if ($this->inferenceConfig !== null) {
			$array['inference_config'] = $this->inferenceConfig;
		}

		return ['inference' => $array];
	}

}
