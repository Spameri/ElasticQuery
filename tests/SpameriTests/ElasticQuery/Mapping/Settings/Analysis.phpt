<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings;

require_once __DIR__ . '/../../../bootstrap.php';


class Analysis extends \Tester\TestCase
{

	public function testToArrayEmpty(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		$array = $analysis->toArray();

		\Tester\Assert::true(isset($array['analyzer']));
		\Tester\Assert::true(isset($array['tokenizer']));
		\Tester\Assert::true(isset($array['filter']));
		\Tester\Assert::same([], $array['analyzer']);
		\Tester\Assert::same([], $array['tokenizer']);
		\Tester\Assert::same([], $array['filter']);
	}


	public function testAnalyzer(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection::class, $analysis->analyzer());
		\Tester\Assert::same(0, $analysis->analyzer()->count());
	}


	public function testTokenizer(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection::class, $analysis->tokenizer());
		\Tester\Assert::same(0, $analysis->tokenizer()->count());
	}


	public function testFilter(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection::class, $analysis->filter());
		\Tester\Assert::same(0, $analysis->filter()->count());
	}


	public function testConstructorWithAnalyzerCollection(): void
	{
		$analyzerCollection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection(
			new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary(),
		);

		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis(
			$analyzerCollection,
		);

		\Tester\Assert::same(1, $analysis->analyzer()->count());
	}


	public function testToArrayWithAnalyzer(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		$analysis->analyzer()->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary());

		$array = $analysis->toArray();

		\Tester\Assert::true(isset($array['analyzer']['englishDictionary']));
		\Tester\Assert::same('custom', $array['analyzer']['englishDictionary']['type']);
	}


	public function testTokenizerCollection(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		$analysis->tokenizer()->add(new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram());

		\Tester\Assert::same(1, $analysis->tokenizer()->count());
		\Tester\Assert::true($analysis->tokenizer()->isValue('edge_ngram'));
	}


	public function testToArrayWithFilter(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		$analysis->filter()->add(new \Spameri\ElasticQuery\Mapping\Filter\Stemmer());

		$array = $analysis->toArray();

		\Tester\Assert::true(isset($array['filter']['stemmer']));
	}


	public function testCompleteAnalysisConfiguration(): void
	{
		$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();

		// Add analyzer
		$analysis->analyzer()->add(new \Spameri\ElasticQuery\Mapping\Analyzer\Custom\EnglishDictionary());

		// Add filter
		$analysis->filter()->add(new \Spameri\ElasticQuery\Mapping\Filter\Stemmer());

		$array = $analysis->toArray();

		\Tester\Assert::true(isset($array['analyzer']['englishDictionary']));
		\Tester\Assert::true(isset($array['filter']['stemmer']));

		// Verify collections are populated
		\Tester\Assert::same(1, $analysis->analyzer()->count());
		\Tester\Assert::same(1, $analysis->filter()->count());
	}

}

(new Analysis())->run();
