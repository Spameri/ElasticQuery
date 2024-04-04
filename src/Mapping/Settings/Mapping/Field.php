<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class Field
	implements \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface
{

	public function __construct(
		private string $name,
		private string $type = \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD,
		private \Spameri\ElasticQuery\Mapping\AnalyzerInterface|null $analyzer = null,
		private bool|null $fieldData = null,
	)
	{
		if ( ! \in_array($type, \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Not allowed type see \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES',
			);
		}
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

		if ($this->fieldData !== null) {
			$array[$this->name]['fielddata'] = $this->fieldData;
		}

		return $array;
	}

}
