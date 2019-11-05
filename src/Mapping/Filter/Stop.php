<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-tokenfilter.html
 */
abstract class Stop implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType() : string
	{
		return 'stop';
	}


	abstract public function getStopWords() : array;


	public function key() : string
	{
		return $this->getName();
	}


	public function toArray() : array
	{
		return [
			$this->getName() => [
				'type'      => $this->getType(),
				'stopwords' => $this->getStopWords(),
			],
		];
	}

}
