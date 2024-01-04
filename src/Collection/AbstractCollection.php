<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Collection;


abstract class AbstractCollection implements CollectionInterface
{

	/**
	 * @var array<\Spameri\ElasticQuery\Entity\EntityInterface>
	 */
	protected array $collection;


	public function __construct(
		\Spameri\ElasticQuery\Entity\EntityInterface ...$collection,
	)
	{
		$this->collection = [];
		foreach ($collection as $item) {
			$this->add($item);
		}
	}


	public function add(
		\Spameri\ElasticQuery\Entity\EntityInterface $item,
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
	): \Spameri\ElasticQuery\Entity\EntityInterface|null
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
