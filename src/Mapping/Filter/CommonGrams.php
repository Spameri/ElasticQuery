<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-common-grams-tokenfilter.html
 */
class CommonGrams implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function __construct(private array $words)
	{
	}


	public function getType(): string
	{
		return 'common_grams';
	}


	public function key(): string
	{
		return 'customCommonGrams';
	}


	public function toArray(): array
	{
		return [
			$this->key() => [
				'type' => $this->getType(),
				'common_words' => $this->words,
			],
		];
	}
}
