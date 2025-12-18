<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class Synonym extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym();

		\Tester\Assert::same('synonym', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym();

		\Tester\Assert::same('customSynonyms', $filter->key());
	}


	public function testGetName(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym();

		\Tester\Assert::same('customSynonyms', $filter->getName());
	}


	public function testToArrayWithEmptySynonyms(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym();

		$expected = [
			'customSynonyms' => [
				'type' => 'synonym',
				'synonyms' => [],
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testToArrayWithSynonyms(): void
	{
		$synonyms = [
			'quick' => 'fast',
			'big' => 'large',
		];
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym($synonyms);

		$result = $filter->toArray();

		\Tester\Assert::same('synonym', $result['customSynonyms']['type']);
		\Tester\Assert::contains('quick => fast', $result['customSynonyms']['synonyms']);
		\Tester\Assert::contains('big => large', $result['customSynonyms']['synonyms']);
	}


	public function testGetSynonyms(): void
	{
		$synonyms = [
			'quick' => 'fast',
			'big' => 'large',
		];
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym($synonyms);

		\Tester\Assert::same($synonyms, $filter->getSynonyms());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Synonym();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}

}

(new Synonym())->run();
