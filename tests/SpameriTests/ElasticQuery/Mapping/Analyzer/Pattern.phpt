<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer;

require_once __DIR__ . '/../../../bootstrap.php';


class Pattern extends \Tester\TestCase
{

	public function testName(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern('[^a-zA-Z]');

		\Tester\Assert::same('customPattern', $analyzer->name());
	}


	public function testGetType(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern('[^a-zA-Z]');

		\Tester\Assert::same('pattern', $analyzer->getType());
	}


	public function testToArrayBasic(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern('[^a-zA-Z]');

		$array = $analyzer->toArray();

		\Tester\Assert::true(isset($array['pattern']));
		\Tester\Assert::same('pattern', $array['pattern']['type']);
		\Tester\Assert::same('[^a-zA-Z]', $array['pattern']['pattern']);
		\Tester\Assert::same(true, $array['pattern']['lowercase']);
		\Tester\Assert::same([], $array['pattern']['stopwords']);
		\Tester\Assert::null($array['pattern']['flags']);
	}


	public function testToArrayWithStopWords(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern(
			'\\W+',
			['the', 'a'],
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same(['the', 'a'], $array['pattern']['stopwords']);
	}


	public function testToArrayWithLowercaseFalse(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern(
			'\\s+',
			[],
			false,
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same(false, $array['pattern']['lowercase']);
	}


	public function testToArrayWithFlags(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern(
			'\\W+',
			[],
			true,
			'CASE_INSENSITIVE|COMMENTS',
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same('CASE_INSENSITIVE|COMMENTS', $array['pattern']['flags']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern(
			'[^a-zA-Z0-9]',
			['stop', 'words'],
			false,
			'UNICODE_CASE',
		);

		$array = $analyzer->toArray();

		\Tester\Assert::same('[^a-zA-Z0-9]', $array['pattern']['pattern']);
		\Tester\Assert::same(['stop', 'words'], $array['pattern']['stopwords']);
		\Tester\Assert::same(false, $array['pattern']['lowercase']);
		\Tester\Assert::same('UNICODE_CASE', $array['pattern']['flags']);
	}


	public function testImplementsAnalyzerInterface(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern('\\W+');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\AnalyzerInterface::class, $analyzer);
	}

}

(new Pattern())->run();
