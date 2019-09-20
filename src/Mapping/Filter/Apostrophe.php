<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-apostrophe-tokenfilter.html
 */
class Apostrophe implements \Spameri\ElasticQuery\Mapping\FilterInterface, \Spameri\ElasticQuery\Collection\Item
{

	public function getType(): string
	{
		return 'apostrophe';
	}


	public function toArray() : array
	{
		// TODO: Implement toArray() method.
	}


	public function key() : string
	{
		// TODO: Implement key() method.
	}

}
