<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class Suggest extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_suggest';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'title' => ['type' => 'text'],
					'suggest' => ['type' => 'completion'],
				],
			],
		];
	}


	public function testTermSuggesterToArray(): void
	{
		$suggester = new \Spameri\ElasticQuery\Options\Suggest\TermSuggester(
			name: 'title_suggest',
			text: 'tring',
			field: 'title',
			size: 3,
		);

		$array = $suggester->toArray();

		\Tester\Assert::same('tring', $array['text']);
		\Tester\Assert::same('title', $array['term']['field']);
		\Tester\Assert::same(3, $array['term']['size']);
	}


	public function testPhraseSuggesterToArray(): void
	{
		$suggester = new \Spameri\ElasticQuery\Options\Suggest\PhraseSuggester(
			name: 'p',
			text: 'noble prize',
			field: 'title',
			size: 5,
			confidence: 0.9,
		);

		\Tester\Assert::same('phrase', \array_keys($suggester->toArray())[1]);
		\Tester\Assert::same(0.9, $suggester->toArray()['phrase']['confidence']);
	}


	public function testCompletionSuggesterToArray(): void
	{
		$suggester = new \Spameri\ElasticQuery\Options\Suggest\CompletionSuggester(
			name: 'c',
			prefix: 'app',
			field: 'suggest',
			skipDuplicates: true,
		);

		\Tester\Assert::same('app', $suggester->toArray()['prefix']);
		\Tester\Assert::true($suggester->toArray()['completion']['skip_duplicates']);
	}


	public function testCreateTermSuggester(): void
	{
		$this->indexDocument(['title' => 'string theory']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			options: new \Spameri\ElasticQuery\Options(
				suggesters: [
					new \Spameri\ElasticQuery\Options\Suggest\TermSuggester(
						name: 'my_suggest',
						text: 'tring',
						field: 'title',
					),
				],
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}


	public function testCreateCompletionSuggester(): void
	{
		$this->indexDocument(['title' => 'apple', 'suggest' => 'apple']);
		$this->indexDocument(['title' => 'application', 'suggest' => 'application']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			options: new \Spameri\ElasticQuery\Options(
				suggesters: [
					new \Spameri\ElasticQuery\Options\Suggest\CompletionSuggester(
						name: 'my_complete',
						prefix: 'app',
						field: 'suggest',
					),
				],
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Suggest())->run();
