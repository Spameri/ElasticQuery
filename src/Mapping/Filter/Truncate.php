<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-truncate-tokenfilter.html
 */
class Truncate implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'truncate';
	}

}
