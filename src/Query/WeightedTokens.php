<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Weighted tokens query — token weights against a sparse_vector field.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-weighted-tokens-query.html
 */
class WeightedTokens implements LeafQueryInterface
{

	/**
	 * @param array<string, float> $tokens Token => weight pairs.
	 * @param array<string, mixed>|null $pruningConfig
	 */
	public function __construct(
		private string $field,
		private array $tokens,
		private array|null $pruningConfig = null,
	)
	{
		if ($tokens === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'WeightedTokens requires at least one token.',
			);
		}
	}


	public function key(): string
	{
		return 'weighted_tokens_' . $this->field;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'tokens' => $this->tokens,
		];

		if ($this->pruningConfig !== null) {
			$body['pruning_config'] = $this->pruningConfig;
		}

		return [
			'weighted_tokens' => [
				$this->field => $body,
			],
		];
	}

}
