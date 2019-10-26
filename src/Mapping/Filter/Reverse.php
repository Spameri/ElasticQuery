<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-reverse-tokenfilter.html
 */
class Reverse implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'reverse';
	}

}
