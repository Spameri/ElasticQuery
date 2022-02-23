<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

/**
 * @see https://github.com/apache/lucene-solr/blob/master/solr/example/files/conf/lang/stopwords_hy.txt
 */
class Armenian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
{

	public function getStopWords(): array
	{
		$parent = parent::getStopWords();
		$parent[] = \Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::ARMENIAN;

		return $parent;
	}


	public function getName(): string
	{
		return 'armenianStopWords';
	}

}
