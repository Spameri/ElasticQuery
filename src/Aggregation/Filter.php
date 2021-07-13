<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html
 */
class Filter extends \Spameri\ElasticQuery\Filter\FilterCollection
	implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function toArray(): array
	{
		$array = parent::toArray();

		if ($array === []) {
			$array['must'] = [];
		}

		return [
			'filter' => [
				'bool' => $array,
			],
		];
	}

}
