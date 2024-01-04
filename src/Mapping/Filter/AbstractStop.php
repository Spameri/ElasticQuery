<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-tokenfilter.html
 */
abstract class AbstractStop implements \Spameri\ElasticQuery\Mapping\FilterInterface
{


	public function __construct(
		private array $extraWords = [],
	)
	{
	}


	public function getType(): string
	{
		return 'stop';
	}


	public function getStopWords(): array
	{
		return $this->extraWords;
	}


	abstract public function getName(): string;


	public function key(): string
	{
		return $this->getName();
	}


	public function toArray(): array
	{
		$stopWords = $this->getStopWords();
		if ($this->extraWords) {
			$stopWords += $this->extraWords;
		}

		return [
			$this->getName() => [
				'type' => $this->getType(),
				'stopwords' => $stopWords,
			],
		];
	}

}
