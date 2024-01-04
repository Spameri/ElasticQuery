<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Filter;


class FilterCollection implements FilterInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\MustCollection|null $mustCollection = null,
	)
	{
		if ($this->mustCollection === null) {
			$this->mustCollection = new \Spameri\ElasticQuery\Query\MustCollection();
		}
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
