<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-whitespace-analyzer.html
 */
class Whitespace implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function getType(): string
	{
		return 'whitespace';
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
