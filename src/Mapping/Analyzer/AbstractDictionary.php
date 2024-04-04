<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

abstract class AbstractDictionary
	implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface,
	\Spameri\ElasticQuery\Collection\Item
{

	protected \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection|null $filter = null;

	public function __construct(
		protected \Spameri\ElasticQuery\Mapping\Filter\AbstractStop|null $stopFilter = null,
	)
	{
	}


	public function key(): string
	{
		return $this->name();
	}


	public function getType(): string
	{
		return 'custom';
	}


	public function tokenizer(): string
	{
		return 'standard';
	}


	public function getStopFilter(): \Spameri\ElasticQuery\Mapping\Filter\AbstractStop|null
	{
		return $this->stopFilter;
	}


	public function toArray(): array
	{
		$filterArray = [];
		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter() as $filter) {
			if ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell) {
				$filterArray[] = $filter->getName();
			} elseif ($filter instanceof \Spameri\ElasticQuery\Mapping\Filter\AbstractStop) {
				$filterArray[] = $filter->getName();
			} else {
				$filterArray[] = $filter->getType();
			}
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
