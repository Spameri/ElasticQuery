<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Analyzer;

require_once __DIR__ . '/../../../bootstrap.php';


class Whitespace extends \Tester\TestCase
{

	public function testName(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Whitespace();

		\Tester\Assert::same('customWhitespace', $analyzer->name());
	}


	public function testGetType(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Whitespace();

		\Tester\Assert::same('whitespace', $analyzer->getType());
	}


	public function testToArray(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Whitespace();

		$array = $analyzer->toArray();

		\Tester\Assert::true(isset($array['whitespace']));
		\Tester\Assert::same('whitespace', $array['whitespace']['type']);
	}


	public function testImplementsAnalyzerInterface(): void
	{
		$analyzer = new \Spameri\ElasticQuery\Mapping\Analyzer\Whitespace();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\AnalyzerInterface::class, $analyzer);
	}

}

(new Whitespace())->run();
