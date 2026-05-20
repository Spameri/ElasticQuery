<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-ttest-aggregation.html
 */
class TTest implements LeafAggregationInterface
{

	public const TYPE_PAIRED = 'paired';
	public const TYPE_HOMOSCEDASTIC = 'homoscedastic';
	public const TYPE_HETEROSCEDASTIC = 'heteroscedastic';

	/**
	 * @param array<string, mixed> $a Population A: ['field' => ..., optional 'filter' => ...]
	 * @param array<string, mixed> $b Population B: ['field' => ..., optional 'filter' => ...]
	 */
	public function __construct(
		private array $a,
		private array $b,
		private string $type = self::TYPE_HETEROSCEDASTIC,
		private string $key = 't_test',
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
		return [
			't_test' => [
				'a' => $this->a,
				'b' => $this->b,
				'type' => $this->type,
			],
		];
	}

}
