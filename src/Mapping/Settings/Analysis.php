<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class Analysis implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	private \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection $analyzer;

	private \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection $tokenizer;

	private \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection $filter;


	public function __construct(
		\Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection|null $analyzer = null,
		\Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection|null $tokenizer = null,
		\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection|null $filter = null,
	)
	{
		if ($analyzer === null) {
			$analyzer = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		}
		if ($tokenizer === null) {
			$tokenizer = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();
		}
		if ($filter === null) {
			$filter = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
		}

		$this->analyzer = $analyzer;
		$this->tokenizer = $tokenizer;
		$this->filter = $filter;
	}


	public function analyzer(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection
	{
		return $this->analyzer;
	}


	public function tokenizer(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection
	{
		return $this->tokenizer;
	}


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	{
		return $this->filter;
	}

	public function toArray(): array
	{
		$analyzerArray = [];
		$tokenizerArray = [];
		$filterArray = [];

		/** @var \Spameri\ElasticQuery\Mapping\AnalyzerInterface $analyzer */
		foreach ($this->analyzer as $analyzer) {
			$analyzerArray[$analyzer->name()] = $analyzer->toArray()[$analyzer->name()];
		}

		/** @var \Spameri\ElasticQuery\Mapping\TokenizerInterface $tokenizer */
		foreach ($this->tokenizer as $tokenizer) {
			$tokenizerArray[$tokenizer->key()] = $tokenizer->toArray()[$tokenizer->key()];
		}

		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter as $filter) {
			if ($filter->toArray()) {
				$filterArray[$filter->key()] = $filter->toArray()[$filter->key()];
			}
		}

		return [
			'analyzer' => $analyzerArray,
			'tokenizer' => $tokenizerArray,
			'filter' => $filterArray,
		];
	}

}
