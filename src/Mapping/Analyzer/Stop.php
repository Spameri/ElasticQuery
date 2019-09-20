<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-analyzer.html
 */
class Stop implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	/**
	 * @var array
	 */
	private $stopWords;


	public function __construct(
		array $stopWords = []
	)
	{
		$this->stopWords = $stopWords;
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
