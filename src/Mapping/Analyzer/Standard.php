<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-standard-analyzer.html
 */
class Standard implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	/**
	 * @var array
	 */
	private $stopWords;

	/**
	 * @var int
	 */
	private $maxTokenLength;


	public function __construct(
		array $stopWords = [],
		int $maxTokenLength = 5,
	)
	{
		$this->stopWords = $stopWords;
		$this->maxTokenLength = $maxTokenLength;
	}


	public function name(): string
	{
		return 'customStandard';
	}


	public function getType(): string
	{
		return 'standard';
	}


	public function toArray(): array
	{
		return [
			$this->getType() => [
				'type' => $this->getType(),
				'max_token_length' => $this->maxTokenLength,
				'stopwords' => $this->stopWords,
			],
		];
	}

}
