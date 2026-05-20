<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore;

require_once __DIR__ . '/../../bootstrap.php';


class Decay extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_function_score_decay';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'location' => ['type' => 'geo_point'],
					'price' => ['type' => 'long'],
				],
			],
		];
	}


	public function testGaussToArray(): void
	{
		$gauss = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay\Gauss(
			field: 'price',
			origin: 100,
			scale: 50,
			offset: 5,
			decay: 0.5,
			multiValueMode: 'avg',
		);

		$array = $gauss->toArray();

		\Tester\Assert::same(100, $array['gauss']['price']['origin']);
		\Tester\Assert::same(50, $array['gauss']['price']['scale']);
		\Tester\Assert::same(5, $array['gauss']['price']['offset']);
		\Tester\Assert::same(0.5, $array['gauss']['price']['decay']);
		\Tester\Assert::same('avg', $array['gauss']['multi_value_mode']);
	}


	public function testLinearToArray(): void
	{
		$linear = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay\Linear(
			field: 'price',
			origin: 100,
			scale: 50,
		);

		\Tester\Assert::same(100, $linear->toArray()['linear']['price']['origin']);
	}


	public function testExpToArray(): void
	{
		$exp = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay\Exp(
			field: 'price',
			origin: 100,
			scale: 50,
		);

		\Tester\Assert::same(100, $exp->toArray()['exp']['price']['origin']);
	}


	public function testCreateGauss(): void
	{
		$this->indexDocument(['price' => 100]);
		$this->indexDocument(['price' => 1000]);

		$gauss = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay\Gauss(
			field: 'price',
			origin: 100,
			scale: 50,
		);

		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection($gauss),
			scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MULTIPLY,
			boostMode: \Spameri\ElasticQuery\FunctionScore::BOOST_MODE_REPLACE,
			boost: 1.0,
			maxBoost: 10.0,
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}


	public function testCreateGeoGauss(): void
	{
		$this->indexDocument(['location' => ['lat' => 50, 'lon' => 14]]);

		$gauss = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay\Gauss(
			field: 'location',
			origin: ['lat' => 50, 'lon' => 14],
			scale: '10km',
			offset: '1km',
			decay: 0.5,
		);

		$functionScore = new \Spameri\ElasticQuery\FunctionScore(
			new \Spameri\ElasticQuery\FunctionScore\FunctionScoreCollection($gauss),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Decay())->run();
