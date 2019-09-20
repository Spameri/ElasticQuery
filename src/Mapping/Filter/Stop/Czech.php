<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

/**
 * @see https://github.com/apache/lucene-solr/blob/master/solr/example/files/conf/lang/stopwords_cz.txt
 */
class Czech extends \Spameri\ElasticQuery\Mapping\Filter\Stop
{

	public function getStopWords() : array
	{
		return [
			\Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::CZECH,
		];
	}

	public function getName(): string
	{
		return 'czechStopWords';
	}

	public function key(): string
	{
		return $this->getName();
	}

}
