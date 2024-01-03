<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-edgengram-tokenfilter.html
 */
class EdgeNgram implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	/**
	 * @var int
	 */
	private $minGram;

	/**
	 * @var int
	 */
	private $maxGram;


	public function __construct(
		int $minGram = 2,
		int $maxGram = 6,
	)
	{
		$this->minGram = $minGram;
		$this->maxGram = $maxGram;
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
