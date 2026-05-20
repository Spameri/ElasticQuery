<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class WeightedTokens extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_weighted_tokens';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'tokens' => ['type' => 'sparse_vector'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$wt = new \Spameri\ElasticQuery\Query\WeightedTokens(
			field: 'tokens',
			tokens: ['lion' => 0.5, 'tiger' => 0.7],
		);

		$array = $wt->toArray();

		\Tester\Assert::same(0.5, $array['weighted_tokens']['tokens']['tokens']['lion']);
	}


	public function testToArrayWithPruning(): void
	{
		$wt = new \Spameri\ElasticQuery\Query\WeightedTokens(
			field: 'tokens',
			tokens: ['cat' => 0.5],
			pruningConfig: ['tokens_freq_ratio_threshold' => 5],
		);

		$array = $wt->toArray();

		\Tester\Assert::same(5, $array['weighted_tokens']['tokens']['pruning_config']['tokens_freq_ratio_threshold']);
	}


	public function testRequiresTokens(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\WeightedTokens('f', []);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$wt = new \Spameri\ElasticQuery\Query\WeightedTokens('f', ['t' => 0.5]);

		\Tester\Assert::same('weighted_tokens_f', $wt->key());
	}

}

(new WeightedTokens())->run();
