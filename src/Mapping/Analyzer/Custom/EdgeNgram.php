<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom;

class EdgeNgram implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface, \Spameri\ElasticQuery\Collection\Item
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	private $filter;

	/**
	 * @var int
	 */
	private $minGram;

	/**
	 * @var int
	 */
	private $maxGram;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
	 */
	private $stopFilter;


	public function __construct(
		int $minGram = 2,
		int $maxGram = 6,
		?\Spameri\ElasticQuery\Mapping\Filter\AbstractStop $stopFilter = NULL
	)
	{
		$this->minGram = $minGram;
		$this->maxGram = $maxGram;
		if ($stopFilter === NULL) {
			$stopFilter = new \Spameri\ElasticQuery\Mapping\Filter\Stop\Czech();
		}
		$this->stopFilter = $stopFilter;
	}


	public function key(): string
	{
		return $this->name();
	}


	public function name(): string
	{
		return 'customEdgeNgram';
	}


	public function getType(): string
	{
		return 'custom';
	}


	public function tokenizer(): string
	{
		return 'standard';
	}


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	{
		if ( ! $this->filter instanceof \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection) {
			$this->filter = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase()
			);
			$this->filter->add($this->stopFilter);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\EdgeNgram($this->minGram, $this->maxGram)
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Unique()
			);
		}

		return $this->filter;
	}


	public function toArray(): array
	{
		$filterArray = [];
		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter() as $filter) {
			$filterArray[] = $filter->getType();
		}

		return [
			$this->name() => [
				'type' => $this->getType(),
				'tokenizer' => $this->tokenizer(),
				'filter' => $filterArray,
			],
		];
	}

}
