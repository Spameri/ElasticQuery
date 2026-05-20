<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-rank-feature-query.html
 */
class RankFeature implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed>|null $function Optional scoring function:
	 *                                            ['saturation' => ['pivot' => 8]] | ['log' => ['scaling_factor' => 4]]
	 *                                            | ['sigmoid' => ['pivot' => 7, 'exponent' => 0.6]] | ['linear' => new \stdClass()].
	 */
	public function __construct(
		private string $field,
		private array|null $function = null,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'rank_feature_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'field' => $this->field,
			'boost' => $this->boost,
		];

		if ($this->function !== null) {
			foreach ($this->function as $name => $config) {
				$body[$name] = $config;
			}
		}

		return [
			'rank_feature' => $body,
		];
	}

}
