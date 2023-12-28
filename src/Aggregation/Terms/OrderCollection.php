<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Terms;

class OrderCollection implements
	\Spameri\ElasticQuery\Collection\SimpleCollectionInterface,
	\Countable
{

	/**
	 * @var array<\Spameri\ElasticQuery\Aggregation\Terms\Order>
	 */
	private array $collection;


	public function __construct(
		\Spameri\ElasticQuery\Aggregation\Terms\Order ... $collection,
	) {
		$this->collection = [];
		foreach ($collection as $item) {
			$this->add($item);
		}
	}


	public function remove(string $key): bool
	{
		if (isset($this->collection[$key])) {
			unset($this->collection[$key]);

			return TRUE;
		}

		return FALSE;
	}


	public function get(string $key): \Spameri\ElasticQuery\Aggregation\Terms\Order|null
	{
		return $this->collection[$key] ?? NULL;
	}


	public function isValue(string $key): bool
	{
		return isset($this->collection[$key]);
	}


	public function keys(): array
	{
		return \array_keys($this->collection);
	}


	public function clear(): void
	{
		$this->collection = [];
	}


	/**
	 * @param \Spameri\ElasticQuery\Aggregation\Terms\Order $item
	 */
	public function add($item): void
	{
		$this->collection[$item->key()] = $item;
	}


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->collection);
	}


	public function count(): int
	{
		return \count($this->collection);
	}


	public function toArray(): array
	{
		if (\count($this->collection) === 1) {
			return \reset($this->collection)->toArray();
		}

		$array = [];
		foreach ($this->collection as $order) {
			$array[] = $order->toArray();
		}

		return $array;
	}

}
