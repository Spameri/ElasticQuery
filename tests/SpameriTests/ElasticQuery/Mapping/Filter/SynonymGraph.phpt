<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class SynonymGraph extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\SynonymGraph();

		\Tester\Assert::same('synonym_graph', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\SynonymGraph();

		\Tester\Assert::same('synonym_graph', $filter->key());
	}


	public function testToArray(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\SynonymGraph();

		$expected = [
			'synonym_graph' => [
				'type' => 'synonym_graph',
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\SynonymGraph();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}

}

(new SynonymGraph())->run();
