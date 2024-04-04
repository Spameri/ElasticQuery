<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Settings\Mapping;

class FieldCollection implements \Spameri\ElasticQuery\Collection\SimpleCollectionInterface
{

	/**
	 * @var array<\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface>
	 */
	protected array $collection;


	public function __construct(
		\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface ...$collection,
	)
	{
		$this->collection = [];
		foreach ($collection as $item) {
			$this->add($item);
		}
	}


	/**
	 * @param \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface $item
	 */
	public function add(
		$item,
	): void
	{
		$this->collection[$item->key()] = $item;
	}


	public function remove(
		string $key,
	): bool
	{
		if (isset($this->collection[$key])) {
			unset($this->collection[$key]);

			return true;
		}

		return false;
	}


	public function get(
		string $key,
	): \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldInterface|null
	{
		return $this->collection[$key] ?? null;
	}


	public function isValue(
		string $key,
	): bool
	{
		if (isset($this->collection[$key])) {
			return true;
		}

		return false;
	}


	public function count(): int
	{
		return \count($this->collection);
	}


	public function keys(): array
	{
		return \array_map('\strval', \array_keys($this->collection));
	}


	public function clear(): void
	{
		$this->collection = [];
	}


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->collection);
	}

}
