<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-edgengram-tokenfilter.html
 */
class EdgeNgram implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	public function __construct(
		private int $minGram = 2,
		private int $maxGram = 6,
	)
	{
	}


	public function getType(): string
	{
		return 'edge_ngram';
	}


	public function key(): string
	{
		return 'customEdgeNgram';
	}


	public function toArray(): array
	{
		return [
			$this->key() => [
				'type' => 'edge_ngram',
				'min_gram' => $this->minGram,
				'max_gram' => $this->maxGram,
			],
		];
	}

}
