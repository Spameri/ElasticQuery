<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class FieldObject
	implements \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldCollection
	 */
	private $fields;


	public function __construct(
		string $name,
		\Spameri\ElasticQuery\Mapping\Settings $fields
	)
	{
		$this->name = $name;
		$this->fields = $fields;
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
			$fields[$field->key()] = $field->toArray();
		}

		return [
			$this->name => [
				'properties' => $fields,
			],
		];
	}

}
