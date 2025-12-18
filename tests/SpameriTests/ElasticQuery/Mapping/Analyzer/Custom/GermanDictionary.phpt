<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer\Custom;

require_once __DIR__ . '/../../../../bootstrap.php';


class GermanDictionary extends \Tester\TestCase
{

	public function testName(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary();

		\Tester\Assert::same('germanDictionary', $analyzer->name());
		\Tester\Assert::same(\Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary::NAME, $analyzer->name());
	}


	public function testFilter(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary();

		$filter = $analyzer->filter();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection::class, $filter);
		\Tester\Assert::true($filter->count() > 0);
	}


	public function testFilterContainsExpectedFilters(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary();

		$filter = $analyzer->filter();

		\Tester\Assert::true($filter->isValue('lowercase'));
		\Tester\Assert::true($filter->isValue('germanStopWords'));
		\Tester\Assert::true($filter->isValue('unique'));
		\Tester\Assert::true($filter->isValue('asciifolding'));
	}


	public function testFilterIsCached(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary();

		$filter1 = $analyzer->filter();
		$filter2 = $analyzer->filter();

		\Tester\Assert::same($filter1, $filter2);
	}


	public function testWithCustomStopFilter(): void
	{
		$customStop = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\GermanDictionary($customStop);

		$filter = $analyzer->filter();

		\Tester\Assert::true($filter->isValue('englishStopWords'));
	}

}

(new GermanDictionary())->run();
