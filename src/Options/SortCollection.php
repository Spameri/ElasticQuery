<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


class SortCollection extends \Spameri\ElasticQuery\Collection\AbstractCollection implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function toArray() : array
	{
		$array = [];

		foreach ($this->collection as $sort) {
			$array[] = $sort->toArray();
		}

		return $array;
	}

}
