<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class QueryCollection implements LeafQueryInterface
{

	private \Spameri\ElasticQuery\Query\MustCollection $mustCollection;

	private \Spameri\ElasticQuery\Query\ShouldCollection $shouldCollection;

	private \Spameri\ElasticQuery\Query\MustNotCollection $mustNotCollection;

	public function __construct(
		private int|string|null $key = null,
		\Spameri\ElasticQuery\Query\MustCollection|null $mustCollection = null,
		\Spameri\ElasticQuery\Query\ShouldCollection|null $shouldCollection = null,
		\Spameri\ElasticQuery\Query\MustNotCollection|null $mustNotCollection = null,
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


	public function must(): \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->mustCollection;
	}


	public function should(): \Spameri\ElasticQuery\Query\ShouldCollection
	{
		return $this->shouldCollection;
	}


	public function mustNot(): \Spameri\ElasticQuery\Query\MustNotCollection
	{
		return $this->mustNotCollection;
	}


	public function addMustQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->mustCollection->add($leafQuery);
	}


	public function addMustNotQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->mustNotCollection->add($leafQuery);
	}


	public function addShouldQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->shouldCollection->add($leafQuery);
	}


	public function key(): string
	{
		if ($this->key) {
			return (string) $this->key;
		}

		return \md5(\serialize($this->toArray()));
	}


	public function toArray(): array
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
