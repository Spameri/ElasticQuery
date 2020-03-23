<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-predicatefilter-tokenfilter.html
 */
class Predicate implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function getType(): string
	{
		return 'predicate_token_filter';
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
