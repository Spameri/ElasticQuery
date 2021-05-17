<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-analyzer.html
 */
class Pattern implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * @var array
	 */
	private $stopWords;

	/**
	 * @var bool
	 */
	private $lowerCase;

	/**
	 * @var string
	 */
	private $flags;


	public function __construct(
		string $pattern,
		array $stopWords = [],
		bool $lowerCase = TRUE,
		string $flags = NULL
	)
	{
		$this->pattern = $pattern;
		$this->stopWords = $stopWords;
		$this->lowerCase = $lowerCase;
		$this->flags = $flags;
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
