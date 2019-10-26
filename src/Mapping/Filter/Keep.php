<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keep-words-tokenfilter.html
 */
class Keep implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'keep';
	}

}
