<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class QueryCollection implements LeafQueryInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Query\MustCollection
	 */
	private $mustCollection;

	/**
	 * @var \Spameri\ElasticQuery\Query\ShouldCollection
	 */
	private $shouldCollection;

	/**
	 * @var \Spameri\ElasticQuery\Query\MustNotCollection
	 */
	private $mustNotCollection;


	public function __construct(
		?\Spameri\ElasticQuery\Query\MustCollection $mustCollection = NULL
		, ?\Spameri\ElasticQuery\Query\ShouldCollection $shouldCollection = NULL
		, ?\Spameri\ElasticQuery\Query\MustNotCollection $mustNotCollection = NULL
	)
	{
		if ( ! $mustCollection) {
			$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		}

		if ( ! $mustNotCollection) {
			$mustNotCollection = new \Spameri\ElasticQuery\Query\MustNotCollection();
		}

		if ( ! $shouldCollection) {
			$shouldCollection = new \Spameri\ElasticQuery\Query\ShouldCollection();
		}

		$this->mustCollection = $mustCollection;
		$this->shouldCollection = $shouldCollection;
		$this->mustNotCollection = $mustNotCollection;
	}


	public function must() : \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->mustCollection;
	}


	public function should() : \Spameri\ElasticQuery\Query\ShouldCollection
	{
		return $this->shouldCollection;
	}


	public function mustNot() : \Spameri\ElasticQuery\Query\MustNotCollection
	{
		return $this->mustNotCollection;
	}


	public function addMustQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery) : void
	{
		$this->mustCollection->add($leafQuery);
	}


	public function addMustNotQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery) : void
	{
		$this->mustNotCollection->add($leafQuery);
	}


	public function addShouldQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery) : void
	{
		$this->shouldCollection->add($leafQuery);
	}


	public function key() : string
	{
		return '';
	}


	public function toArray() : array
	{
		$array = [];
		/** @var \Spameri\ElasticQuery\Query\LeafQueryInterface $item */
		foreach ($this->mustCollection as $item) {
			$array['bool']['must'][] = $item->toArray();
		}

		/** @var \Spameri\ElasticQuery\Query\LeafQueryInterface $item */
		foreach ($this->mustNotCollection as $item) {
			$array['bool']['must_not'][] = $item->toArray();
		}

		/** @var \Spameri\ElasticQuery\Query\LeafQueryInterface $item */
		foreach ($this->shouldCollection as $item) {
			$array['bool']['should'][] = $item->toArray();
		}

		return $array;
	}

}
