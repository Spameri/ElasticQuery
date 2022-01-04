<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping;

class Settings implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Analysis
	 */
	private $analysis;

	/**
	 * @var string
	 */
	private $indexName;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\Mapping
	 */
	private $mapping;

	/**
	 * @var \Spameri\ElasticQuery\Mapping\Settings\AliasCollection
	 */
	private $alias;


	public function __construct(
		string $indexName,
		?\Spameri\ElasticQuery\Mapping\Settings\Analysis $analysis = NULL,
		?\Spameri\ElasticQuery\Mapping\Settings\Mapping $mapping = NULL,
		?\Spameri\ElasticQuery\Mapping\Settings\AliasCollection $alias = NULL
	)
	{
		if ($analysis === NULL) {
			$analysis = new \Spameri\ElasticQuery\Mapping\Settings\Analysis();
		}
		if ($mapping === NULL) {
			$mapping = new \Spameri\ElasticQuery\Mapping\Settings\Mapping($indexName);
		}
		if ($alias === NULL) {
			$alias = new \Spameri\ElasticQuery\Mapping\Settings\AliasCollection();
		}

		$this->analysis = $analysis;
		$this->indexName = $indexName;
		$this->mapping = $mapping;
		$this->alias = $alias;
	}


	public function indexName(): string
	{
		return $this->indexName;
	}


	public function analysis(): \Spameri\ElasticQuery\Mapping\Settings\Analysis
	{
		return $this->analysis;
	}

	public function mapping(): \Spameri\ElasticQuery\Mapping\Settings\Mapping
	{
		return $this->mapping;
	}

	public function addMappingField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\Field $field): void
	{
		$this->mapping->addField($field);
	}


	public function addMappingFieldKeyword(string $name): void
	{
		$this->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				$name,
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_KEYWORD
			)
		);
	}


	public function addMappingFieldFloat(string $name): void
	{
		$this->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				$name,
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_FLOAT
			)
		);
	}


	public function addMappingFieldInteger(string $name): void
	{
		$this->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				$name,
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_INTEGER
			)
		);
	}


	public function addMappingFieldBoolean(string $name): void
	{
		$this->addMappingField(
			new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
				$name,
				\Spameri\Elastic\Model\ValidateMapping\AllowedValues::TYPE_BOOLEAN
			)
		);
	}


	public function addMappingFieldObject(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject $fieldObject): void
	{
		$this->mapping->addFieldObject($fieldObject);
	}


	public function addMappingSubField(\Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields $subFields): void
	{
		$this->mapping->addSubField($subFields);
	}


	public function addAnalyzer(\Spameri\ElasticQuery\Mapping\AnalyzerInterface $analyzer): void
	{
		$this->analysis->analyzer()->add($analyzer);

		if ($analyzer instanceof \Spameri\ElasticQuery\Mapping\CustomAnalyzerInterface) {
			foreach ($analyzer->filter() as $filter) {
				$this->addFilter($filter);
			}
		}
	}


	public function addTokenizer(\Spameri\ElasticQuery\Mapping\TokenizerInterface $tokenizer): void
	{
		$this->analysis->tokenizer()->add($tokenizer);
	}


	public function addFilter(\Spameri\ElasticQuery\Mapping\FilterInterface $filter): void
	{
		$this->analysis->filter()->add($filter);
	}


	public function toArray(): array
	{
		$array = [
			'settings' => [
				'analysis' => $this->analysis->toArray(),
			],
		];

		if ($this->alias->count()) {
			/** @var \Spameri\ElasticQuery\Mapping\Settings\Alias $alias */
			foreach ($this->alias as $alias) {
				$array['aliases'][$alias->name()] = $alias->toArray();
			}
		}

		if ($this->mapping) {
			$array = \array_merge($array, $this->mapping->toArray());
		}

		return $array;
	}

}
