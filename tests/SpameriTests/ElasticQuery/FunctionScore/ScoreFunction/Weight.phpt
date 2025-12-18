<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\FunctionScore\ScoreFunction;

require_once __DIR__ . '/../../../bootstrap.php';


class Weight extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'premium');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(2.0, $term);

		$array = $weight->toArray();

		\Tester\Assert::same(2.0, $array['weight']);
		\Tester\Assert::true(isset($array['filter']['term']['status']));
	}


	public function testToArrayWithDifferentWeight(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('featured', true);
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(5.0, $term);

		$array = $weight->toArray();

		\Tester\Assert::same(5.0, $array['weight']);
	}


	public function testToArrayWithRangeFilter(): void
	{
		$range = new \Spameri\ElasticQuery\Query\Range('price', 0.0, 100.0);
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(1.5, $range);

		$array = $weight->toArray();

		\Tester\Assert::same(1.5, $array['weight']);
		\Tester\Assert::true(isset($array['filter']['range']['price']));
	}


	public function testToArrayWithExistsFilter(): void
	{
		$exists = new \Spameri\ElasticQuery\Query\Exists('discount');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(3.0, $exists);

		$array = $weight->toArray();

		\Tester\Assert::same(3.0, $array['weight']);
		\Tester\Assert::same('discount', $array['filter']['exists']['field']);
	}


	public function testToArrayWithMatchFilter(): void
	{
		$match = new \Spameri\ElasticQuery\Query\ElasticMatch('category', 'electronics');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(2.5, $match);

		$array = $weight->toArray();

		\Tester\Assert::same(2.5, $array['weight']);
		\Tester\Assert::true(isset($array['filter']['match']));
	}


	public function testKey(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('type', 'featured');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(2.0, $term);

		\Tester\Assert::same('weight_term_type_featured', $weight->key());
	}


	public function testWeightMethod(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('active', true);
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(4.5, $term);

		\Tester\Assert::same(4.5, $weight->weight());
	}


	public function testLeafQueryMethod(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('field', 'value');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(1.0, $term);

		\Tester\Assert::same($term, $weight->leafQuery());
	}


	public function testFilterStructure(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('category', 'books');
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(2.0, $term);

		$array = $weight->toArray();

		// Verify that filter contains the full query array
		\Tester\Assert::same($term->toArray(), $array['filter']);
	}


	public function testZeroWeight(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('hidden', true);
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(0.0, $term);

		$array = $weight->toArray();

		\Tester\Assert::same(0.0, $array['weight']);
	}


	public function testNegativeWeight(): void
	{
		$term = new \Spameri\ElasticQuery\Query\Term('deprecated', true);
		$weight = new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(-1.0, $term);

		$array = $weight->toArray();

		\Tester\Assert::same(-1.0, $array['weight']);
	}

}

(new Weight())->run();
