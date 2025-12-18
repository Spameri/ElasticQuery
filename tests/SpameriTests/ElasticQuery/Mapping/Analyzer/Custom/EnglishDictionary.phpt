<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer\Custom;

require_once __DIR__ . '/../../../../bootstrap.php';


class EnglishDictionary extends \Tester\TestCase
{

	public function testName(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::same('englishDictionary', $analyzer->name());
		\Tester\Assert::same(\Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary::NAME, $analyzer->name());
	}


	public function testKey(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::same('englishDictionary', $analyzer->key());
	}


	public function testGetType(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::same('custom', $analyzer->getType());
	}


	public function testTokenizer(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::same('standard', $analyzer->tokenizer());
	}


	public function testFilter(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$filter = $analyzer->filter();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection::class, $filter);
		\Tester\Assert::true($filter->count() > 0);
	}


	public function testToArray(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$array = $analyzer->toArray();

		\Tester\Assert::true(isset($array['englishDictionary']));
		\Tester\Assert::same('custom', $array['englishDictionary']['type']);
		\Tester\Assert::same('standard', $array['englishDictionary']['tokenizer']);
		\Tester\Assert::true(isset($array['englishDictionary']['filter']));
		\Tester\Assert::type('array', $array['englishDictionary']['filter']);
	}


	public function testToArrayContainsFilters(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$array = $analyzer->toArray();

		$filters = $array['englishDictionary']['filter'];

		// Should contain lowercase, stop, hunspell, unique, asciifolding filters
		\Tester\Assert::contains('lowercase', $filters);
		\Tester\Assert::contains('asciifolding', $filters);
		\Tester\Assert::contains('unique', $filters);
	}


	public function testGetStopFilterNull(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::null($analyzer->getStopFilter());
	}


	public function testGetStopFilterCustom(): void
	{
		$customStopFilter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary($customStopFilter);

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Filter\AbstractStop::class, $analyzer->getStopFilter());
	}


	public function testImplementsCustomAnalyzerInterface(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface::class, $analyzer);
	}


	public function testImplementsItemInterface(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		\Tester\Assert::type(\Spameri\ElasticQuery\Collection\Item::class, $analyzer);
	}


	public function testFilterIsCached(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary();

		$filter1 = $analyzer->filter();
		$filter2 = $analyzer->filter();

		\Tester\Assert::same($filter1, $filter2);
	}

}

(new EnglishDictionary())->run();
