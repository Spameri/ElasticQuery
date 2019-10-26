<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-remove-duplicates-tokenfilter.html
 */
class RemoveDuplicates implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'remove_duplicates';
	}

}
