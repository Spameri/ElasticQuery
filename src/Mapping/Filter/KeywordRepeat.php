<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-repeat-tokenfilter.html
 */
class KeywordRepeat implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'keyword_repeat';
	}


	public function key(): string
	{
		return $this->getType();
	}


	public function toArray(): array
	{
		return [
			$this->key() => [
				'type' => $this->getType(),
			],
		];
	}

}
