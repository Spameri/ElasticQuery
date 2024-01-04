<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class NestedObject implements \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface
{

	public function __construct(
		private string $name,
		private \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection $fields,
	)
	{
	}


	public function key(): string
	{
		return $this->name;
	}


	public function toArray(): array
	{
		$fields = [];
		/** @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface $field */
		foreach ($this->fields as $field) {
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject) {
				$fields[$field->key()] = $field->toArray();
				continue;
			}
			if ($field instanceof \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection) {
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
			'properties' => $fields,
			'type' => 'nested',
		];
	}

}
