<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-analyzer.html
 */
class Keyword implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function getType(): string
	{
		return 'keyword';
	}


	public function name(): string
	{
		return 'customKeywod';
	}


	public function toArray(): array
	{
		return [
			$this->getType() => [
				'type' => $this->getType(),
			],
		];
	}

}
