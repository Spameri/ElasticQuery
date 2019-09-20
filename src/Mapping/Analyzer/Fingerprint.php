<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-fingerprint-analyzer.html
 */
class Fingerprint implements \Spameri\ElasticQuery\Mapping\AnalyzerInterface
{

	/**
	 * @var array
	 */
	private $stopWords;

	/**
	 * @var string
	 */
	private $separator;

	/**
	 * @var int
	 */
	private $maxOutputSize;


	public function __construct(
		array $stopWords = [],
		string $separator = ' ',
		int $maxOutputSize = 255
	)
	{
		$this->stopWords = $stopWords;
		$this->separator = $separator;
		$this->maxOutputSize = $maxOutputSize;
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
