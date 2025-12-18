<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Analysis;

require_once __DIR__ . '/../../../../bootstrap.php';


class AnalyzerCollection extends \Tester\TestCase
{

	public function testEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testConstructorWithAnalyzers(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::contains('englishDictionary', $collection->keys());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary());

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue('englishDictionary'));
	}


	public function testAddReplacesSameKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary());
		$collection->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary());

		\Tester\Assert::same(1, $collection->count());
	}


	public function testRemove(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		$result = $collection->remove('englishDictionary');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::false($collection->isValue('englishDictionary'));
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testGet(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		$analyzer = $collection->get('englishDictionary');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\AnalyzerInterface::class, $analyzer);
		\Tester\Assert::same('englishDictionary', $analyzer->name());
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$analyzer = $collection->get('non_existent');

		\Tester\Assert::null($analyzer);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		\Tester\Assert::true($collection->isValue('englishDictionary'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testCount(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary(),
		);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary(),
		);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('englishDictionary', $keys);
		\Tester\Assert::contains('germanDictionary', $keys);
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(1, $iterator->count());
	}


	public function testForeach(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary(),
		);

		$keys = [];
		foreach ($collection as $key => $analyzer) {
			$keys[] = $key;
			\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\AnalyzerInterface::class, $analyzer);
		}

		\Tester\Assert::count(2, $keys);
	}

}

(new AnalyzerCollection())->run();
