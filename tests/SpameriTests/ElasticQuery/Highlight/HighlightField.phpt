<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Highlight;

require_once __DIR__ . '/../../bootstrap.php';


class HighlightField extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_highlight_field';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'title' => ['type' => 'text'],
					'body' => ['type' => 'text'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$field = new \Spameri\ElasticQuery\Highlight\HighlightField(
			field: 'title',
			type: 'unified',
			numberOfFragments: 3,
			fragmentSize: 150,
			boundaryScanner: 'sentence',
			encoder: 'html',
			fragmenter: 'span',
			noMatchSize: 100,
			order: 'score',
			phraseLimit: 256,
			requireFieldMatch: false,
			preTags: ['<b>'],
			postTags: ['</b>'],
		);

		$array = $field->toArray();

		\Tester\Assert::same('unified', $array['type']);
		\Tester\Assert::same(3, $array['number_of_fragments']);
		\Tester\Assert::same(150, $array['fragment_size']);
		\Tester\Assert::same('sentence', $array['boundary_scanner']);
		\Tester\Assert::same('html', $array['encoder']);
		\Tester\Assert::same('span', $array['fragmenter']);
		\Tester\Assert::same(100, $array['no_match_size']);
		\Tester\Assert::same('score', $array['order']);
		\Tester\Assert::same(256, $array['phrase_limit']);
		\Tester\Assert::false($array['require_field_match']);
		\Tester\Assert::same(['<b>'], $array['pre_tags']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'quick brown fox', 'body' => 'jumps over the lazy dog']);

		$fields = new \Spameri\ElasticQuery\Highlight\HighlightFieldCollection();
		$fields->add(new \Spameri\ElasticQuery\Highlight\HighlightField(
			field: 'title',
			numberOfFragments: 0,
			type: 'unified',
		));
		$fields->add(new \Spameri\ElasticQuery\Highlight\HighlightField(
			field: 'body',
			numberOfFragments: 3,
			fragmentSize: 50,
		));

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			highlight: new \Spameri\ElasticQuery\Highlight(
				preTags: ['<em>'],
				postTags: ['</em>'],
				fields: $fields,
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'fox'));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new HighlightField())->run();
