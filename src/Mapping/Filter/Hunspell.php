<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-hunspell-tokenfilter.html
 */
abstract class Hunspell implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'hunspell';
	}


	abstract public function getLocale() : string;


	abstract public function getName() : string;


	public function key() : string
	{
		return $this->getName();
	}


	public function toArray() : array
	{
		return [
			$this->getName() => [
				'type' => $this->getType(),
				'locale' => $this->getLocale(),
			],
		];
	}

}
