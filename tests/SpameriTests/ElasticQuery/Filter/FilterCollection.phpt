<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Filter;

require_once __DIR__ . '/../../bootstrap.php';


class FilterCollection extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();

		$array = $filter->toArray();

		\Tester\Assert::same([], $array);
	}


	public function testToArrayWithSingleMust(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::count(1, $array['bool']['must']);
		\Tester\Assert::same('active', $array['bool']['must'][0]['term']['status']['value']);
	}


	public function testToArrayWithMultipleMust(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'published'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('type', 'article'));

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::count(2, $array['bool']['must']);
	}


	public function testToArrayWithRange(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Range('price', 10.0, 100.0));

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::true(isset($array['bool']['must'][0]['range']['price']));
	}


	public function testToArrayWithExists(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Exists('email'));

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::same('email', $array['bool']['must'][0]['exists']['field']);
	}


	public function testMustMethod(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();

		$mustCollection = $filter->must();

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\MustCollection::class, $mustCollection);
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();

		\Tester\Assert::same('', $filter->key());
	}


	public function testConstructorWithMustCollection(): void
	{
		$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		$mustCollection->add(new \Spameri\ElasticQuery\Query\Term('field', 'value'));

		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection($mustCollection);

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::count(1, $array['bool']['must']);
	}


	public function testMustCollectionOperations(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('field1', 'value1'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('field2', 'value2'));

		\Tester\Assert::same(2, $filter->must()->count());

		$filter->must()->remove('term_field1_value1');

		\Tester\Assert::same(1, $filter->must()->count());
	}


	public function testMustCollectionGet(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$term = new \Spameri\ElasticQuery\Query\Term('status', 'active');
		$filter->must()->add($term);

		$retrieved = $filter->must()->get('term_status_active');

		\Tester\Assert::same($term, $retrieved);
	}


	public function testMustCollectionIsValue(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		\Tester\Assert::true($filter->must()->isValue('term_status_active'));
		\Tester\Assert::false($filter->must()->isValue('non_existent'));
	}


	public function testMustCollectionClear(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('field1', 'value1'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('field2', 'value2'));

		\Tester\Assert::same(2, $filter->must()->count());

		$filter->must()->clear();

		\Tester\Assert::same(0, $filter->must()->count());
		\Tester\Assert::same([], $filter->toArray());
	}


	public function testMustCollectionKeys(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('alpha', 'a'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('beta', 'b'));

		$keys = $filter->must()->keys();

		\Tester\Assert::contains('term_alpha_a', $keys);
		\Tester\Assert::contains('term_beta_b', $keys);
	}


	public function testMustCollectionIterate(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('a', '1'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('b', '2'));

		$count = 0;
		foreach ($filter->must() as $query) {
			\Tester\Assert::type(\Spameri\ElasticQuery\Query\LeafQueryInterface::class, $query);
			$count++;
		}

		\Tester\Assert::same(2, $count);
	}


	public function testFilterContextStructure(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Range('age', 18.0, 65.0));

		$array = $filter->toArray();

		// Filter context should produce bool.must structure
		\Tester\Assert::true(isset($array['bool']));
		\Tester\Assert::true(isset($array['bool']['must']));
		\Tester\Assert::true(\is_array($array['bool']['must']));
	}


	public function testComplexFilterScenario(): void
	{
		$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();

		// Add multiple filter conditions
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Term('category', 'electronics'));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Range('price', 100.0, 1000.0));
		$filter->must()->add(new \Spameri\ElasticQuery\Query\Exists('stock'));

		$array = $filter->toArray();

		\Tester\Assert::count(3, $array['bool']['must']);

		// Verify term filter
		$termFound = false;
		foreach ($array['bool']['must'] as $mustClause) {
			if (isset($mustClause['term']['category'])) {
				$termFound = true;
				\Tester\Assert::same('electronics', $mustClause['term']['category']['value']);
			}
		}
		\Tester\Assert::true($termFound);
	}

}

(new FilterCollection())->run();
