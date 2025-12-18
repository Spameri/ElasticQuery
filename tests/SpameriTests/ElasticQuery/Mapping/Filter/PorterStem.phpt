<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class PorterStem extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\PorterStem();

		\Tester\Assert::same('porter_stem', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\PorterStem();

		\Tester\Assert::same('customPorterStem', $filter->key());
	}


	public function testToArray(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\PorterStem();

		$expected = [
			'customPorterStem' => [
				'type' => 'porter_stem',
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\PorterStem();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}

}

(new PorterStem())->run();
