<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Options
{

	/**
	 * @var int
	 */
	private $size;
	/**
	 * @var int
	 */
	private $from;
	/**
	 * @var \Spameri\ElasticQuery\Options\SortCollection
	 */
	private $sort;
	/**
	 * @var int
	 */
	private $minScore;


	public function __construct(
		?int $size = NULL,
		?int $from = NULL,
		?\Spameri\ElasticQuery\Options\SortCollection $sort = NULL,
		float $minScore = NULL
	)
	{
		$this->size = $size;
		$this->from = $from;
		$this->sort = $sort ?: new \Spameri\ElasticQuery\Options\SortCollection();
		$this->minScore = $minScore;
	}


	public function changeFrom(int $from) : void
	{
		$this->from = $from;
	}


	public function changeSize(int $size) : void
	{
		$this->size = $size;
	}


	public function toArray() : array
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

		return $array;
	}


	public function sort() : \Spameri\ElasticQuery\Options\SortCollection
	{
		return $this->sort;
	}

}
