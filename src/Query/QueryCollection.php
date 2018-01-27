<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class QueryCollection extends \Spameri\ElasticQuery\Query\AbstractLeafQuery
{

	/**
	 * @var \Spameri\ElasticQuery\Query\MustCollection
	 */
	private $mustCollection;
	/**
	 * @var \Spameri\ElasticQuery\Query\ShouldCollection
	 */
	private $shouldCollection;


	public function __construct(
		?\Spameri\ElasticQuery\Query\MustCollection $mustCollection = NULL,
		?\Spameri\ElasticQuery\Query\ShouldCollection $shouldCollection = NULL
	)
	{
		if ( ! $mustCollection) {
			$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		}

		if ( ! $shouldCollection) {
			$shouldCollection = new \Spameri\ElasticQuery\Query\ShouldCollection();
		}

		$this->mustCollection = $mustCollection;
		$this->shouldCollection = $shouldCollection;
	}


	public function must() : \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->mustCollection;
	}


	public function should() : \Spameri\ElasticQuery\Query\ShouldCollection
	{
		return $this->shouldCollection;
	}


	public function key() : string
	{
		return '';
	}


	public function toArray() : array
	{
		$array = [];
		/** @var \Spameri\ElasticQuery\Query\AbstractLeafQuery $item */
		foreach ($this->mustCollection as $item) {
			$array['bool']['must'][] = $item->toArray();
		}
		/** @var \Spameri\ElasticQuery\Query\AbstractLeafQuery $item */
		foreach ($this->shouldCollection as $item) {
			$array['bool']['should'][] = $item->toArray();
		}

		return $array;
	}

}
