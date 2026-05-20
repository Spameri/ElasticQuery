<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class Rescore extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_rescore';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['title' => ['type' => 'text']]]];
	}


	public function testToArray(): void
	{
		$rescore = new \Spameri\ElasticQuery\Options\Rescore(
			query: new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'foo'),
			windowSize: 50,
			queryWeight: 0.7,
			rescoreQueryWeight: 1.2,
			scoreMode: \Spameri\ElasticQuery\Options\Rescore::SCORE_MODE_TOTAL,
		);

		$array = $rescore->toArray();

		\Tester\Assert::same(50, $array['window_size']);
		\Tester\Assert::same(0.7, $array['query']['query_weight']);
		\Tester\Assert::same('total', $array['query']['score_mode']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['title' => 'hello world']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			options: new \Spameri\ElasticQuery\Options(
				rescore: [
					new \Spameri\ElasticQuery\Options\Rescore(
						query: new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'world'),
						windowSize: 10,
					),
				],
			),
		);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'hello'));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Rescore())->run();
