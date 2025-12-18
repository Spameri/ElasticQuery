<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class Ngram extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Ngram();

		\Tester\Assert::same('ngram', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Ngram();

		\Tester\Assert::same('ngram', $filter->key());
	}


	public function testToArray(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Ngram();

		$expected = [
			'ngram' => [
				'type' => 'ngram',
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Ngram();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}

}

(new Ngram())->run();
