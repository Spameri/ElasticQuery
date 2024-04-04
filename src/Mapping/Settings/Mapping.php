<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class Mapping implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	private \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection $fields;

	public function __construct(
		private string $indexName,
		\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection|null $fields = null,
		private bool $dynamic = true,
	)
	{

		if ($fields === null) {
			$fields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection();
		}

		$this->fields = $fields;
	}

	public function enableStrictMapping(): void
	{
		$this->dynamic = false;
	}

	public function getIndexName(): string
	{
		return $this->indexName;
	}


	public function fields(): \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection
	{
		return $this->fields;
	}


	public function addField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\Field $field): void
	{
		$this->fields->add($field);
	}


	public function addFieldObject(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject $fieldObject): void
	{
		$this->fields->add($fieldObject);
	}


	public function addNestedObject(\Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject $fieldObject): void
	{
		$this->fields->add($fieldObject);
	}


	public function removeFieldObject(string $field): void
	{
		$this->fields->remove($field);
	}


	public function addSubField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields $subFields): void
	{
		$this->fields->add($subFields);
	}


	public function removeSubField(string $subFields): void
	{
		$this->fields->remove($subFields);
	}


	public function toArray(): array
	{
		$fields = [];
		/** @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface $field */
		foreach ($this->fields as $field) {
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields) {
				$fields[$field->key()] = $field->toArray();
				continue;
			}
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject) {
				$fields[$field->key()] = $field->toArray();
				continue;
			}
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject) {
				$fields[$field->key()] = $field->toArray();
				continue;
			}

			$fields[$field->key()] = $field->toArray()[$field->key()];
		}

		return [
			'mappings' => [
				'properties' => $fields,
				'dynamic' => $this->dynamic,
			],
		];
	}

}
