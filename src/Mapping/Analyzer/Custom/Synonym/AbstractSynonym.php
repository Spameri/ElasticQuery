<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom\Synonym;

abstract class AbstractSynonym
	implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface,
	\Spameri\ElasticQuery\Collection\Item
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	protected $filter;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
	 */
	protected $stopFilter;

	/**
	 * @var array
	 */
	protected $synonyms;


	public function __construct(
		?\Spameri\ElasticQuery\Mapping\Filter\AbstractStop $stopFilter = NULL,
		array $synonyms
	)
	{
		$this->stopFilter = $stopFilter;
		$this->synonyms = $synonyms;
	}


	public function key() : string
	{
		return $this->name();
	}


	public function getType() : string
	{
		return 'custom';
	}


	public function tokenizer() : string
	{
		return 'standard';
	}


	public function toArray() : array
	{
		$filterArray = [];
		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter() as $filter) {
			if ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\Synonym) {
				$filterArray[] = $filter->getName();

			} elseif ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\AbstractStop) {
				$filterArray[] = $filter->getName();

			} else {
				$filterArray[] = $filter->getType();
			}
		}

		return [
			$this->name() => [
				'type'      => $this->getType(),
				'tokenizer' => $this->tokenizer(),
				'filter'    => $filterArray,
			],
		];
	}


}
