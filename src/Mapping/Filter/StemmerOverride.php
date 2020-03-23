<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stemmer-override-tokenfilter.html
 */
class StemmerOverride implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'stemmer_override';
	}


	public function key(): string
	{
		return $this->getType();
	}


	public function toArray(): array
	{
		return [
			$this->key() => [
				'type' => $this->getType(),
			],
		];
	}

}
