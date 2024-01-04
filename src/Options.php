<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Options
{

	private \Spameri\ElasticQuery\Options\SortCollection $sort;


	public function __construct(
		private int|null $size = null,
		private int|null $from = null,
		\Spameri\ElasticQuery\Options\SortCollection|null $sort = null,
		private float|null $minScore = null,
		private bool $includeVersion = false,
		private string|null $scroll = null,
		private string|null $scrollId = null,
	)
	{
		$this->sort = $sort ?: new \Spameri\ElasticQuery\Options\SortCollection();
	}


	public function changeFrom(int $from): void
	{
		$this->from = $from;
	}


	public function changeSize(int $size): void
	{
		$this->size = $size;
	}


	public function sort(): \Spameri\ElasticQuery\Options\SortCollection
	{
		return $this->sort;
	}


	public function scroll(): string|null
	{
		return $this->scroll;
	}


	public function startScroll(
		string $scroll,
	): void
	{
		$this->scroll = $scroll;
	}


	public function scrollId(): string|null
	{
		return $this->scrollId;
	}


	public function scrollInitialized(
		string $scrollId,
	): void
	{
		$this->scrollId = $scrollId;
	}


	public function toArray(): array
	{
		$array = [];

		if ($this->from !== null) {
			$array['from'] = $this->from;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		foreach ($this->sort as $item) {
			$array['sort'][] = $item->toArray();
		}

		if ($this->minScore) {
			$array['min_score'] = $this->minScore;
		}

		if ($this->includeVersion === true) {
			$array['version'] = $this->includeVersion;
		}

		if ($this->scrollId !== null) {
			$array['scroll_id'] = $this->scrollId;
			$array['scroll'] = $this->scroll;
		}

		return $array;
	}

}
