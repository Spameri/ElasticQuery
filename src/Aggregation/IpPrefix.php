<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-ipprefix-aggregation.html
 */
class IpPrefix implements LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int $prefixLength,
		private bool|null $isIpv6 = null,
		private bool|null $appendPrefixLength = null,
		private bool|null $keyed = null,
		private int|null $minDocCount = null,
	)
	{
	}


	public function key(): string
	{
		return 'ip_prefix_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'prefix_length' => $this->prefixLength,
		];

		if ($this->isIpv6 !== null) {
			$array['is_ipv6'] = $this->isIpv6;
		}

		if ($this->appendPrefixLength !== null) {
			$array['append_prefix_length'] = $this->appendPrefixLength;
		}

		if ($this->keyed !== null) {
			$array['keyed'] = $this->keyed;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		return ['ip_prefix' => $array];
	}

}
