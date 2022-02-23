<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document\Body;


class Settings implements \Spameri\ElasticQuery\Document\BodyInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection
	 */
	private $analyzer;
	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	 */
	private $filter;


	public function __construct(
		\Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection $analyzer,
		\Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection $filter
	)
	{
		$this->analyzer = $analyzer;
		$this->filter = $filter;
	}


	public function toArray(): array
	{
		$analyzers = [];
		/** @var \Spameri\ElasticQuery\Mapping\AnalyzerInterface&\Spameri\ElasticQuery\Collection\Item $analyzer */
		foreach ($this->analyzer as $analyzer) {
			$analyzers[$analyzer->key()] = $analyzer->toArray()[$analyzer->key()];
		}

		$filters = [];
		/** @var \Spameri\ElasticQuery\Mapping\FilterInterface $filter */
		foreach ($this->filter as $filter) {
			if ($filter->toArray() === [] ) {
				continue;
			}
			$filters[$filter->key()] = $filter->toArray()[$filter->key()];
		}

		return [
			'settings' => [
				'analysis' => [
					'analyzer' => $analyzers,
					'tokenizer' => [

					],
					'filter' => $filters,
				],
			],
		];
	}

}
