<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

require_once __DIR__ . '/../bootstrap.php';


class Options extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$options = new \Spameri\ElasticQuery\Options();

		$array = $options->toArray();

		\Tester\Assert::same([], $array);
	}


	public function testToArrayWithSize(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			size: 10,
		);

		$array = $options->toArray();

		\Tester\Assert::same(['size' => 10], $array);
	}


	public function testToArrayWithFrom(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			from: 20,
		);

		$array = $options->toArray();

		\Tester\Assert::same(['from' => 20], $array);
	}


	public function testToArrayWithSizeAndFrom(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			size: 10,
			from: 20,
		);

		$array = $options->toArray();

		\Tester\Assert::same(['from' => 20, 'size' => 10], $array);
	}


	public function testToArrayWithMinScore(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			minScore: 0.5,
		);

		$array = $options->toArray();

		\Tester\Assert::same(['min_score' => 0.5], $array);
	}


	public function testToArrayWithVersion(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			includeVersion: true,
		);

		$array = $options->toArray();

		\Tester\Assert::same(['version' => true], $array);
	}


	public function testToArrayWithSort(): void
	{
		$sortCollection = new \Spameri\ElasticQuery\Options\SortCollection();
		$sortCollection->add(new \Spameri\ElasticQuery\Options\Sort('created_at', \Spameri\ElasticQuery\Options\Sort::DESC));

		$options = new \Spameri\ElasticQuery\Options(
			sort: $sortCollection,
		);

		$array = $options->toArray();

		\Tester\Assert::true(isset($array['sort']));
		\Tester\Assert::count(1, $array['sort']);
		\Tester\Assert::same('DESC', $array['sort'][0]['created_at']['order']);
	}


	public function testChangeSize(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			size: 10,
		);

		$options->changeSize(50);

		$array = $options->toArray();

		\Tester\Assert::same(['size' => 50], $array);
	}


	public function testChangeFrom(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			from: 0,
		);

		$options->changeFrom(100);

		$array = $options->toArray();

		\Tester\Assert::same(['from' => 100], $array);
	}


	public function testSort(): void
	{
		$options = new \Spameri\ElasticQuery\Options();

		$options->sort()->add(new \Spameri\ElasticQuery\Options\Sort('name', \Spameri\ElasticQuery\Options\Sort::ASC));

		$array = $options->toArray();

		\Tester\Assert::true(isset($array['sort']));
		\Tester\Assert::count(1, $array['sort']);
	}


	public function testScroll(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			scroll: '1m',
		);

		\Tester\Assert::same('1m', $options->scroll());
	}


	public function testStartScroll(): void
	{
		$options = new \Spameri\ElasticQuery\Options();

		$options->startScroll('2m');

		\Tester\Assert::same('2m', $options->scroll());
	}


	public function testScrollId(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			scrollId: 'DXF1ZXJ5QW5kRmV0Y2gBAAAAAA',
		);

		\Tester\Assert::same('DXF1ZXJ5QW5kRmV0Y2gBAAAAAA', $options->scrollId());
	}


	public function testScrollInitialized(): void
	{
		$options = new \Spameri\ElasticQuery\Options();

		$options->scrollInitialized('DXF1ZXJ5QW5kRmV0Y2gBAAAAAA');

		\Tester\Assert::same('DXF1ZXJ5QW5kRmV0Y2gBAAAAAA', $options->scrollId());
	}


	public function testToArrayWithScrollId(): void
	{
		$options = new \Spameri\ElasticQuery\Options(
			scroll: '1m',
			scrollId: 'DXF1ZXJ5QW5kRmV0Y2gBAAAAAA',
		);

		$array = $options->toArray();

		\Tester\Assert::same('DXF1ZXJ5QW5kRmV0Y2gBAAAAAA', $array['scroll_id']);
		\Tester\Assert::same('1m', $array['scroll']);
	}


	public function testToArrayFullOptions(): void
	{
		$sortCollection = new \Spameri\ElasticQuery\Options\SortCollection();
		$sortCollection->add(new \Spameri\ElasticQuery\Options\Sort('created_at', \Spameri\ElasticQuery\Options\Sort::DESC));

		$options = new \Spameri\ElasticQuery\Options(
			size: 25,
			from: 50,
			sort: $sortCollection,
			minScore: 0.75,
			includeVersion: true,
		);

		$array = $options->toArray();

		\Tester\Assert::same(50, $array['from']);
		\Tester\Assert::same(25, $array['size']);
		\Tester\Assert::same(0.75, $array['min_score']);
		\Tester\Assert::same(true, $array['version']);
		\Tester\Assert::true(isset($array['sort']));
	}

}

(new Options())->run();
