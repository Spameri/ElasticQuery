<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

/**
 * @see https://github.com/apache/lucene-solr/blob/master/solr/example/files/conf/lang/stopwords_sk.txt
 */
class Slovak extends \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
{

	public function getStopWords(): array
	{
		return [
			\Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::CZECH,
		];
	}


	public function getName(): string
	{
		return 'slovakStopWords';
	}

}
