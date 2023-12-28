<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom;

class CommonGrams implements \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface, \Spameri\ElasticQuery\Collection\Item
{

	public const NAME = 'customCommonGrams';

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	private $filter;

	/**
	 * @var array<string>
	 */
	private $commonGramWords;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
	 */
	private $stopFilter;


	public function __construct(
		array $commonGramWords,
		\Spameri\ElasticQuery\Mapping\Filter\AbstractStop|null $stopFilter = NULL,
	)
	{
		$this->commonGramWords = $commonGramWords;
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
		return self::NAME;
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
			$this->filter->add($this->stopFilter);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\CommonGrams($this->commonGramWords),
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
