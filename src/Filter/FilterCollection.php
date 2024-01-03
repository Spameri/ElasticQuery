<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Filter;


class FilterCollection implements FilterInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Query\MustCollection
	 */
	private $mustCollection;


	public function __construct(
		\Spameri\ElasticQuery\Query\MustCollection|null $mustCollection = NULL,
	)
	{
		if ( ! $mustCollection) {
			$mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		}

		$this->mustCollection = $mustCollection;
	}


	public function must(): \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->mustCollection;
	}


	public function key(): string
	{
		return '';
	}


	public function toArray(): array
	{
		$array = [];
		/** @var \Spameri\ElasticQuery\Query\LeafQueryInterface $item */
		foreach ($this->mustCollection as $item) {
			$array['bool']['must'][] = $item->toArray();
		}

		return $array;
	}

}
