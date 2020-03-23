<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern_replace-tokenfilter.html
 */
class PatternReplace implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'pattern_replace';
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
