<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-analyzer.html
 */
class Stop implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function __construct(
		private array $stopWords = [],
	)
	{
	}

	public function name(): string
	{
		return 'customStop';
	}


	public function getType(): string
	{
		return 'stop';
	}


	public function toArray(): array
	{
		return [
			$this->getType() => [
				'type' => $this->getType(),
				'stopwords' => $this->stopWords,
			],
		];
	}

}
