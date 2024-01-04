<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-analyzer.html
 */
class Pattern implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function __construct(
		private string $pattern,
		private array $stopWords = [],
		private bool $lowerCase = true,
		private string|null $flags = null,
	)
	{
	}


	public function name(): string
	{
		return 'customPattern';
	}


	public function getType(): string
	{
		return 'pattern';
	}


	public function toArray(): array
	{
		return [
			$this->getType() => [
				'type' => $this->getType(),
				'pattern' => $this->pattern,
				'flags' => $this->flags,
				'lowercase' => $this->lowerCase,
				'stopwords' => $this->stopWords,
			],
		];
	}

}
