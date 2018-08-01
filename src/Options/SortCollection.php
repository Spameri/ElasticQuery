<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


class SortCollection extends \Spameri\ElasticQuery\Query\AbstractLeafQuery
{

	/**
	 * @var \Spameri\ElasticQuery\Options\Sort[]
	 */
	private $collection;


	public function __construct(
		Sort ... $collection
	)
	{
		$this->collection = $collection;
	}


	public function key() : string
	{
		return '';
	}


	public function toArray() : array
	{
		$array = [];

		foreach ($this->collection as $sort) {
			$array[] = $sort->toArray();
		}

		return $array;
	}

}
