<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Filter;

require_once __DIR__ . '/../../../bootstrap.php';


class EdgeNgram extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram();

		\Tester\Assert::same('edge_ngram', $filter->getType());
	}


	public function testKey(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram();

		\Tester\Assert::same('customEdgeNgram', $filter->key());
	}


	public function testToArrayWithDefaults(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram();

		$expected = [
			'customEdgeNgram' => [
				'type' => 'edge_ngram',
				'min_gram' => 2,
				'max_gram' => 6,
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testToArrayWithCustomValues(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram(3, 10);

		$expected = [
			'customEdgeNgram' => [
				'type' => 'edge_ngram',
				'min_gram' => 3,
				'max_gram' => 10,
			],
		];

		\Tester\Assert::same($expected, $filter->toArray());
	}


	public function testImplementsFilterInterface(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\FilterInterface::class, $filter);
	}


	public function testMinGramOnly(): void
	{
		$filter = new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram(1);

		$result = $filter->toArray();

		\Tester\Assert::same(1, $result['customEdgeNgram']['min_gram']);
		\Tester\Assert::same(6, $result['customEdgeNgram']['max_gram']);
	}

}

(new EdgeNgram())->run();
