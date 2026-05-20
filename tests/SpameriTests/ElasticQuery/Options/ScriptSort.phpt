<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class ScriptSort extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_script_sort';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		$sort = new \Spameri\ElasticQuery\Options\ScriptSort(
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value"),
			type: \Spameri\ElasticQuery\Options\ScriptSort::TYPE_NUMBER,
			order: \Spameri\ElasticQuery\Options\Sort::DESC,
		);

		$array = $sort->toArray();

		\Tester\Assert::same('number', $array['_script']['type']);
		\Tester\Assert::same('DESC', $array['_script']['order']);
		\Tester\Assert::same("doc['price'].value", $array['_script']['script']['source']);
	}


	public function testRejectsInvalidType(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Options\ScriptSort(
					script: new \Spameri\ElasticQuery\Script(source: ''),
					type: 'invalid',
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);
		$this->indexDocument(['price' => 50]);

		$scriptSort = new \Spameri\ElasticQuery\Options\ScriptSort(
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value"),
			order: \Spameri\ElasticQuery\Options\Sort::DESC,
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->options()->sort()->add($scriptSort);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new ScriptSort())->run();
