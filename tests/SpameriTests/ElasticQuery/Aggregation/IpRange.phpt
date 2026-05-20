<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class IpRange extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_ip_range';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['ip' => ['type' => 'ip']]]];
	}


	public function testToArrayFromTo(): void
	{
		$ipRange = new \Spameri\ElasticQuery\Aggregation\IpRange(
			field: 'ip',
			ranges: [
				new \Spameri\ElasticQuery\Aggregation\IpRange\IpRangeValue('low', null, '10.0.0.5'),
				new \Spameri\ElasticQuery\Aggregation\IpRange\IpRangeValue('high', '10.0.0.5', null),
			],
		);

		$array = $ipRange->toArray();

		\Tester\Assert::same('ip', $array['ip_range']['field']);
		\Tester\Assert::count(2, $array['ip_range']['ranges']);
	}


	public function testToArrayCidr(): void
	{
		$ipRange = new \Spameri\ElasticQuery\Aggregation\IpRange(
			field: 'ip',
			ranges: [
				new \Spameri\ElasticQuery\Aggregation\IpRange\IpRangeValue('private', mask: '10.0.0.0/8'),
			],
		);

		$array = $ipRange->toArray();

		\Tester\Assert::same('10.0.0.0/8', $array['ip_range']['ranges'][0]['mask']);
	}


	public function testRequiresFromToOrMask(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\IpRange\IpRangeValue('k');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['ip' => '10.0.0.1']);
		$this->indexDocument(['ip' => '192.168.0.1']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'ip_buckets',
			null,
			new \Spameri\ElasticQuery\Aggregation\IpRange(
				field: 'ip',
				ranges: [
					new \Spameri\ElasticQuery\Aggregation\IpRange\IpRangeValue('private', mask: '10.0.0.0/8'),
				],
			),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new IpRange())->run();
