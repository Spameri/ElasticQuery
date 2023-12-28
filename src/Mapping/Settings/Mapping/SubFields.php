<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class SubFields
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
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection
	 */
	private $fields;


	public function __construct(
		string $name,
		string $type = \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_KEYWORD,
		\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection|null $fields = NULL,
	)
	{
		$this->name = $name;
		if ($fields === NULL) {
			$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		}

		if ( ! \in_array($type, \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES, TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Not allowed type see \Spameri\ElasticQuery\Mapping\AllowedValues::TYPES',
			);
		}
		$this->type = $type;
		$this->fields = $fields;
	}


	public function key(): string
	{
		return $this->name;
	}


	public function getFields(): \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection
	{
		return $this->fields;
	}


	public function addMappingField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\Field $field): void
	{
		$this->fields->add($field);
	}


	public function removeMappingField(string $field): void
	{
		$this->fields->remove($field);
	}


	public function toArray(): array
	{
		$array['fields'] = [];
		$array['type'] = $this->type;

		/** @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field $field */
		foreach ($this->fields as $field) {
			$array['fields'][$field->key()] = $field->toArray()[$field->key()];
		}

		return $array;
	}

}
