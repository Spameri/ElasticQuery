<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class MappingOld implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	private \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection $fields;


	public function __construct(
		private string $indexName,
		\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection|null $fields = NULL,
	)
	{

		if ($fields === NULL) {
			$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		}

		$this->fields = $fields;
	}


	public function addField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\Field $field): void
	{
		$this->fields->add($field);
	}


	public function addFieldObject(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject $fieldObject): void
	{
		$this->fields->add($fieldObject);
	}


	public function addSubField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields $subFields): void
	{
		$this->fields->add($subFields);
	}


	public function toArray(): array
	{
		$fields = [];
		/** @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface $field */
		foreach ($this->fields as $field) {
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields) {
				$fields[$field->key()] = $field->toArray();

			} else {
				$fields[$field->key()] = $field->toArray()[$field->key()];
			}
		}

		return [
			'mappings' => [
				$this->indexName => [
					'properties' => $fields,
				],
			],
		];
	}

}
