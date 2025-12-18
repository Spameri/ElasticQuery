<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter\Stop;

require_once __DIR__ . '/../../../../bootstrap.php';


class English extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		\Tester\Assert::same('stop', $filter->getType());
	}


	public function testGetName(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		\Tester\Assert::same('englishStopWords', $filter->getName());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		\Tester\Assert::same('englishStopWords', $filter->key());
	}


	public function testGetStopWordsContainsEnglishConstant(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		$stopWords = $filter->getStopWords();

		\Tester\Assert::contains(\Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::ENGLISH, $stopWords);
	}


	public function testToArray(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		$result = $filter->toArray();

		\Tester\Assert::true(isset($result['englishStopWords']));
		\Tester\Assert::same('stop', $result['englishStopWords']['type']);
		\Tester\Assert::true(isset($result['englishStopWords']['stopwords']));
	}


	public function testWithExtraWords(): void
	{
		$extraWords = ['custom', 'words'];
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English($extraWords);

		$stopWords = $filter->getStopWords();

		\Tester\Assert::contains('custom', $stopWords);
		\Tester\Assert::contains('words', $stopWords);
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}


	public function testExtendsAbstractStop(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\English();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\Filter\AbstractStop::class, $filter);
	}

}

(new English())->run();
