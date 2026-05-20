<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class TextExpansion extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_text_expansion';


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
		$te = new \Spameri\ElasticQuery\Query\TextExpansion(
			field: 'tokens',
			modelId: '.elser_model_2',
			modelText: 'big cat',
		);

		$array = $te->toArray();

		\Tester\Assert::same('.elser_model_2', $array['text_expansion']['tokens']['model_id']);
		\Tester\Assert::same('big cat', $array['text_expansion']['tokens']['model_text']);
	}


	public function testWithPruningConfig(): void
	{
		$te = new \Spameri\ElasticQuery\Query\TextExpansion(
			field: 'tokens',
			modelId: '.elser_model_2',
			modelText: 'q',
			pruningConfig: ['tokens_freq_ratio_threshold' => 5, 'only_score_pruned_tokens' => false],
			boost: 2.0,
		);

		$array = $te->toArray();

		\Tester\Assert::same(5, $array['text_expansion']['tokens']['pruning_config']['tokens_freq_ratio_threshold']);
		\Tester\Assert::same(2.0, $array['text_expansion']['tokens']['boost']);
	}


	public function testKey(): void
	{
		$te = new \Spameri\ElasticQuery\Query\TextExpansion('f', 'm', 'q');

		\Tester\Assert::same('text_expansion_f', $te->key());
	}

}

(new TextExpansion())->run();
