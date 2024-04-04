<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-fingerprint-analyzer.html
 */
class Fingerprint implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function __construct(
		private array $stopWords = [],
		private string $separator = ' ',
		private int $maxOutputSize = 255,
	)
	{
	}


	public function name(): string
	{
		return 'customFingerprint';
	}


	public function getType(): string
	{
		return 'fingerprint';
	}


	public function toArray(): array
	{
		return [
			$this->getType() => [
				'type' => $this->getType(),
				'stopwords' => $this->stopWords,
				'separator' => $this->separator,
				'max_output_size' => $this->maxOutputSize,
			],
		];
	}

}
