<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Document\Body;

require_once __DIR__ . '/../../../bootstrap.php';


class Settings extends \Tester\TestCase
{

	public function testToArrayBasicStructure(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']));
		\Tester\Assert::true(isset($array['settings']['analysis']));
		\Tester\Assert::true(isset($array['settings']['analysis']['analyzer']));
		\Tester\Assert::true(isset($array['settings']['analysis']['tokenizer']));
		\Tester\Assert::true(isset($array['settings']['analysis']['filter']));
	}


	public function testToArrayWithEmptyCollections(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::same([], $array['settings']['analysis']['analyzer']);
		\Tester\Assert::same([], $array['settings']['analysis']['filter']);
	}


	public function testToArrayWithAnalyzer(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$analyzerCollection->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\Lowercase());

		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']['analysis']['analyzer']['customLowercase']));
		\Tester\Assert::same('custom', $array['settings']['analysis']['analyzer']['customLowercase']['type']);
	}


	public function testToArrayWithFilter(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
		$filterCollection->add(new \Spameri\ElasticQuery\Mapping\Filter\Lowercase());

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']['analysis']['filter']['lowercase']));
		\Tester\Assert::same('lowercase', $array['settings']['analysis']['filter']['lowercase']['type']);
	}


	public function testToArrayWithMultipleFilters(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();

		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
		$filterCollection->add(new \Spameri\ElasticQuery\Mapping\Filter\Lowercase());
		$filterCollection->add(new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding());

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::true(isset($array['settings']['analysis']['filter']['lowercase']));
		\Tester\Assert::true(isset($array['settings']['analysis']['filter']['asciifolding']));
	}


	public function testToArrayWithAnalyzerAndFilters(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$analyzerCollection->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\Lowercase());

		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
		$filterCollection->add(new \Spameri\ElasticQuery\Mapping\Filter\Lowercase());
		$filterCollection->add(new \Spameri\ElasticQuery\Mapping\Filter\Unique());

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		// Check analyzer
		\Tester\Assert::true(isset($array['settings']['analysis']['analyzer']['customLowercase']));

		// Check filters
		\Tester\Assert::true(isset($array['settings']['analysis']['filter']['lowercase']));
		\Tester\Assert::true(isset($array['settings']['analysis']['filter']['unique']));
	}


	public function testImplementsBodyInterface(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		\Tester\Assert::type(\Spameri\ElasticQuery\Document\BodyInterface::class, $settings);
	}


	public function testToArrayTokenizerIsEmptyArray(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		$filterCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();

		$settings = new \Spameri\ElasticQuery\Document\Body\Settings($analyzerCollection, $filterCollection);

		$array = $settings->toArray();

		\Tester\Assert::same([], $array['settings']['analysis']['tokenizer']);
	}

}

(new Settings())->run();
