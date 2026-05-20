<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class IpPrefix extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_ip_prefix';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['ip' => ['type' => 'ip']]]];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\IpPrefix(
			field: 'ip',
			prefixLength: 16,
			appendPrefixLength: true,
			keyed: false,
			minDocCount: 1,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('ip', $array['ip_prefix']['field']);
		\Tester\Assert::same(16, $array['ip_prefix']['prefix_length']);
		\Tester\Assert::true($array['ip_prefix']['append_prefix_length']);
		\Tester\Assert::same(1, $array['ip_prefix']['min_doc_count']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['ip' => '10.0.0.1']);
		$this->indexDocument(['ip' => '10.0.5.20']);
		$this->indexDocument(['ip' => '192.168.0.1']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'by_prefix', null, new \Spameri\ElasticQuery\Aggregation\IpPrefix('ip', 8),
		));

		\Tester\Assert::same(3, $this->search($elasticQuery)->stats()->total());
	}

}

(new IpPrefix())->run();
