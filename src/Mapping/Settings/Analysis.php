<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class Analysis implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection
	 */
	private $analyzer;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection
	 */
	private $tokenizer;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	private $filter;


	public function __construct(
		?\Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection $analyzer = NULL,
		?\Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection $tokenizer = NULL,
		?\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection $filter = NULL
	)
	{
		if ($analyzer === NULL) {
			$analyzer = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection();
		}
		if ($tokenizer === NULL) {
			$tokenizer = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();
		}
		if ($filter === NULL) {
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
