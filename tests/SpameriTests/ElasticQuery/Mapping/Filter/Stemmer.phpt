<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class Stemmer extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stemmer();

		\Tester\Assert::same('stemmer', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stemmer();

		\Tester\Assert::same('stemmer', $filter->key());
	}


	public function testToArray(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stemmer();

		$expected = [
			'stemmer' => [
				'type' => 'stemmer',
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\Stemmer();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}

}

(new Stemmer())->run();
