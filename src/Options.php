<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Options
{

	/**
	 * @var ?int
	 */
	private $size;
	/**
	 * @var ?int
	 */
	private $from;
	/**
	 * @var \Spameri\ElasticQuery\Options\SortCollection
	 */
	private $sort;
	/**
	 * @var ?float
	 */
	private $minScore;
	/**
	 * @var bool
	 */
	private $includeVersion;
	/**
	 * @var string|null
	 */
	private $scroll;
	/**
	 * @var string|null
	 */
	private $scrollId;


	public function __construct(
		?int $size = NULL,
		?int $from = NULL,
		?\Spameri\ElasticQuery\Options\SortCollection $sort = NULL,
		?float $minScore = NULL,
		bool $includeVersion = FALSE,
		?string $scroll = NULL,
		?string $scrollId = NULL
	)
	{
		$this->size = $size;
		$this->from = $from;
		$this->sort = $sort ?: new \Spameri\ElasticQuery\Options\SortCollection();
		$this->minScore = $minScore;
		$this->includeVersion = $includeVersion;
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


	public function scroll(): ?string
	{
		return $this->scroll;
	}


	public function startScroll(
		string $scroll
	): void
	{
		$this->scroll = $scroll;
	}


	public function scrollId(): ?string
	{
		return $this->scrollId;
	}


	public function scrollInitialized(
		string $scrollId
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
