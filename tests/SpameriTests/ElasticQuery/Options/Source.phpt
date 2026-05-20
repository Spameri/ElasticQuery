<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class Source extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_source';


	public function testValueFalse(): void
	{
		$source = new \Spameri\ElasticQuery\Options\Source(enabled: false);

		\Tester\Assert::false($source->value());
	}


	public function testValueIncludesExcludes(): void
	{
		$source = new \Spameri\ElasticQuery\Options\Source(
			includes: ['title', 'body'],
			excludes: ['password'],
		);

		$value = $source->value();
		\Tester\Assert::same(['title', 'body'], $value['includes']);
		\Tester\Assert::same(['password'], $value['excludes']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'hello', 'secret' => 'shh']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			options: new \Spameri\ElasticQuery\Options(
				source: new \Spameri\ElasticQuery\Options\Source(
					includes: ['title'],
					excludes: ['secret'],
				),
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Source())->run();
