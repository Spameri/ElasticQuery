<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
 */
class Range implements LeafAggregationInterface
{

	/**
	 * @var string
	 */
	private $field;
	
	/**
	 * @var bool
	 */
	private $keyed;
	
	/**
	 * @var \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	 */
	private $ranges;


	public function __construct(
		string $field
		, bool $keyed = FALSE
		, \Spameri\ElasticQuery\Aggregation\RangeValueCollection $rangeValueCollection = NULL
	)
	{
		$this->field = $field;
		$this->keyed = $keyed;
		$this->ranges = $rangeValueCollection ?: new \Spameri\ElasticQuery\Aggregation\RangeValueCollection();
	}


	public function key() : string
	{
		return $this->field;
	}


	public function toArray() : array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->keyed === TRUE) {
			$array['keyed'] = TRUE;
		}

		foreach ($this->ranges as $range) {
			$array['ranges'][] = $range->toArray();
		}

		return [
			'range' => $array,
		];
	}


	public function ranges() : \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	{
		return $this->ranges;
	}

}
