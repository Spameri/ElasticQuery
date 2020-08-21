<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings;

class Mapping implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var string
	 */
	private $indexName;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection
	 */
	private $fields;


	public function __construct(
		string $indexName,
		?\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection $fields = NULL
	)
	{
		$this->indexName = $indexName;

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
