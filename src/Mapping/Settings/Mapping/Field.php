<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class Field
	implements \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\AnalyzerInterface|null
	 */
	private $analyzer;

	/**
	 * @var bool|null
	 */
	private $fieldData;


	public function __construct(
		string $name,
		string $type = \Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_KEYWORD,
		?\Spameri\ElasticQuery\Mapping\AnalyzerInterface $analyzer = NULL,
		?bool $fieldData = NULL
	)
	{
		$this->name = $name;
		if ( ! \in_array($type, \Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPES, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Not allowed type see \Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPES'
			);
		}
		$this->type = $type;
		$this->analyzer = $analyzer;
		$this->fieldData = $fieldData;
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
