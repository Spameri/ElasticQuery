<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class CombinedFields extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_combined_fields';


	public function testToArray(): void
	{
		$cf = new \Spameri\ElasticQuery\Query\CombinedFields(
			fields: ['title', 'abstract', 'body'],
			query: 'distributed search',
			operator: 'and',
		);

		$array = $cf->toArray();

		\Tester\Assert::same(['title', 'abstract', 'body'], $array['combined_fields']['fields']);
		\Tester\Assert::same('and', $array['combined_fields']['operator']);
	}


	public function testAutoGenerateSynonyms(): void
	{
		$cf = new \Spameri\ElasticQuery\Query\CombinedFields(
			fields: ['body'],
			query: 'x',
			autoGenerateSynonymsPhraseQuery: false,
		);

		\Tester\Assert::false($cf->toArray()['combined_fields']['auto_generate_synonyms_phrase_query']);
	}


	public function testRequiresFields(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\CombinedFields([], 'x');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$cf = new \Spameri\ElasticQuery\Query\CombinedFields(['title', 'body'], 'q');
		\Tester\Assert::same('combined_fields_title-body_q', $cf->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'distributed search', 'body' => 'engine']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\CombinedFields(
						fields: ['title', 'body'],
						query: 'distributed search',
						operator: 'and',
						autoGenerateSynonymsPhraseQuery: true,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new CombinedFields())->run();
