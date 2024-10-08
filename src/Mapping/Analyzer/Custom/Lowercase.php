<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom;

class Lowercase implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface, \Spameri\ElasticQuery\Collection\Item
{

	protected \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection|null $filter = null;


	public function key(): string
	{
		return $this->name();
	}


	public function name(): string
	{
		return 'customLowercase';
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
				new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding(),
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Unique(),
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
