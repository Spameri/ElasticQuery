<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

/**
 * @see https://github.com/apache/lucene-solr/tree/master/solr/example/files/conf/lang
 */
class Finnish extends \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
{

	public function getStopWords(): array
	{
		$parent = parent::getStopWords();
		$parent[] = \Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::FINNISH;

		return $parent;
	}


	public function getName(): string
	{
		return 'finnishStopWords';
	}

}
