<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-synonym-tokenfilter.html
 */
class Synonym implements \Spameri\ElasticQuery\Mapping\FilterInterface
{

	/**
	 * @var array<string>
	 */
	private $synonyms;


	public function __construct(
		array $synonyms = []
	)
	{
		$this->synonyms = $synonyms;
	}


	public function getType(): string
	{
		return 'synonym';
	}


	public function getSynonyms(): array
	{
		return $this->synonyms;
	}


	public function getName(): string
	{
		return 'customSynonyms';
	}


	public function key(): string
	{
		return $this->getName();
	}


	public function toArray(): array
	{
		return [
			$this->getName() => [
				'type'      => $this->getType(),
				'synonyms' => $this->synonyms,
			],
		];
	}

}
