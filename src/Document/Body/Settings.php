<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document\Body;


class Settings implements \Spameri\ElasticQuery\Document\BodyInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Mapping\Settings\Analysis\AnalyzerCollection $analyzer,
		private \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection $filter,
	)
	{
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
			if ($filter->toArray() === []) {
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
