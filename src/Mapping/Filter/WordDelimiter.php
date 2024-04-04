<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-word-delimiter-tokenfilter.html
 */
class WordDelimiter implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'word_delimiter';
	}


	public function key(): string
	{
		return 'customWordDelimiter';
	}


	public function toArray(): array
	{
		return [
			$this->key() => [
				'type' => $this->getType(),
				'catenate_all' => true,
			],
		];
	}

}
