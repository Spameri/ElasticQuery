<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Options
{

	private ?int $size = NULL;
	private ?int $from = NULL;
	private \Spameri\ElasticQuery\Options\SortCollection $sort;
	private ?float $minScore = NULL;
	private ?string $scroll = NULL;
	private ?string $scrollId = NULL;


	public function __construct(
		int|null $size = NULL,
		int|null $from = NULL,
		\Spameri\ElasticQuery\Options\SortCollection|null $sort = NULL,
		float|null $minScore = NULL,
		private bool $includeVersion = FALSE,
		string|null $scroll = NULL,
		string|null $scrollId = NULL,
	)
	{
		$this->size = $size;
		$this->from = $from;
		$this->sort = $sort ?: new \Spameri\ElasticQuery\Options\SortCollection();
		$this->minScore = $minScore;
		$this->scroll = $scroll;
		$this->scrollId = $scrollId;
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

		if ($this->from !== NULL) {
			$array['from'] = $this->from;
		}

		if ($this->size !== NULL) {
			$array['size'] = $this->size;
		}

		foreach ($this->sort as $item) {
			$array['sort'][] = $item->toArray();
		}

		if ($this->minScore) {
			$array['min_score'] = $this->minScore;
		}

		if ($this->includeVersion === TRUE) {
			$array['version'] = $this->includeVersion;
		}

		if ($this->scrollId !== NULL) {
			$array['scroll_id'] = $this->scrollId;
			$array['scroll'] = $this->scroll;
		}

		return $array;
	}

}
