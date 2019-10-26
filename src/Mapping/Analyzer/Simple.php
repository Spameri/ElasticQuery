<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-simple-analyzer.html
 */
class Simple implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	public function getType(): string
	{
		return 'simple';
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
