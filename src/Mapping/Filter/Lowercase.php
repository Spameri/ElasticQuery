<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lowercase-tokenfilter.html
 */
class Lowercase implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'lowercase';
	}


	public function toArray(): array
	{
		return [];
	}


	public function key(): string
	{
		return $this->getType();
	}

}
