<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer;

require_once __DIR__ . '/../../../bootstrap.php';


class Standard extends \Tester\TestCase
{

	public function testName(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard();

		\Tester\Assert::same('customStandard', $analyzer->name());
	}


	public function testGetType(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard();

		\Tester\Assert::same('standard', $analyzer->getType());
	}


	public function testToArrayDefault(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard();

		$array = $analyzer->toArray();

		\Tester\Assert::true(isset($array['standard']));
		\Tester\Assert::same('standard', $array['standard']['type']);
		\Tester\Assert::same(5, $array['standard']['max_token_length']);
		\Tester\Assert::same([], $array['standard']['stopwords']);
	}


	public function testToArrayWithStopWords(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(
			['the', 'a', 'an'],
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same(['the', 'a', 'an'], $array['standard']['stopwords']);
	}


	public function testToArrayWithMaxTokenLength(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(
			[],
			10,
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same(10, $array['standard']['max_token_length']);
	}


	public function testToArrayWithBothOptions(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(
			['stop', 'words'],
			15,
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same(['stop', 'words'], $array['standard']['stopwords']);
		\Tester\Assert::same(15, $array['standard']['max_token_length']);
	}


	public function testImplementsAnalyzerInterface(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Standard();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\AnalyzerInterface::class, $analyzer);
	}

}

(new Standard())->run();
