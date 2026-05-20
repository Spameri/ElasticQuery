<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class CategorizeText extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_categorize_text';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['message' => ['type' => 'text']]]];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\CategorizeText(
			field: 'message',
			maxUniqueTokens: 100,
			similarityThreshold: 0.7,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('message', $array['categorize_text']['field']);
		\Tester\Assert::same(100, $array['categorize_text']['max_unique_tokens']);
		\Tester\Assert::same(0.7, $array['categorize_text']['similarity_threshold']);
	}


	public function testCreate(): void
	{
		// categorize_text requires platinum-tier license; skip on basic
		$this->indexDocument(['message' => 'Failed to connect to server']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'patterns', null, new \Spameri\ElasticQuery\Aggregation\CategorizeText('message'),
		));

		try {
			\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
		} catch (\Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped $e) {
			if (\str_contains($e->getMessage(), 'license')) {
				\Tester\Environment::skip('categorize_text requires platinum-tier license');
			}
			throw $e;
		}
	}

}

(new CategorizeText())->run();
