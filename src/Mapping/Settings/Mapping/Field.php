<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class Field
	implements \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface
{

	private ?\Spameri\ElasticQuery\Mapping\AnalyzerInterface $analyzer = NULL;

	private ?bool $fieldData = NULL;


	public function __construct(
		private string $name,
		private string $type = \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD,
		\Spameri\ElasticQuery\Mapping\AnalyzerInterface|null $analyzer = NULL,
		bool|null $fieldData = NULL,
	)
	{
		if ( ! \in_array($type, \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Not allowed type see \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES',
			);
		}
		$this->analyzer = $analyzer;
		$this->fieldData = $fieldData;
	}


	public function changeAnalyzer(\Spameri\ElasticQuery\Mapping\AnalyzerInterface $newAnalyzer): void
	{
		$this->analyzer = $newAnalyzer;
	}


	public function key(): string
	{
		return $this->name;
	}


	public function toArray(): array
	{
		$array = [
			$this->name => [
				'type' => $this->type,
			],
		];

		if ($this->analyzer) {
			$array[$this->name]['analyzer'] = $this->analyzer->name();
		}

		if ($this->fieldData !== NULL) {
			$array[$this->name]['fielddata'] = $this->fieldData;
		}

		return $array;
	}

}
