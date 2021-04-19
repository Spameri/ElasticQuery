<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-synonym-tokenfilter.html
 */
class FileSynonym implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	/**
	 * @var string
	 */
	private $path;


	public function __construct(
		string $path
	)
	{
		$this->path = $path;
	}


	public function getType(): string
	{
		return 'synonym';
	}


	public function getPath() : string
	{
		return $this->path;
	}


	public function getName() : string
	{
		return 'customSynonyms';
	}


	public function key() : string
	{
		return $this->getName();
	}


	public function toArray() : array
	{
		return [
			$this->getName() => [
				'type'      => $this->getType(),
				'synonyms_path' => $this->path,
			],
		];
	}

}
