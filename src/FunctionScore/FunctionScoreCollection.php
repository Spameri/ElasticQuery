<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore;

class FunctionScoreCollection implements \Spameri\ElasticQuery\Collection\SimpleCollectionInterface
{

	/**
	 * @var array<\Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface>
	 */
	private array $collection;


	public function __construct(
		\Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface ... $collection
	)
	{
		$this->collection = [];
		foreach ($collection as $item) {
			$this->add($item);
		}
	}


	/**
	 * @param \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface $item
	 */
	public function add(
		$item
	): void
	{
		$this->collection[$item->key()] = $item;
	}


	public function remove(
		string $key
	): bool
	{
		if (isset($this->collection[$key])) {
			unset($this->collection[$key]);

			return TRUE;
		}

		return FALSE;
	}


	public function get(
		string $key
	): ?\Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
	{
		return $this->collection[$key] ?? NULL;
	}


	public function isValue(
		string $key
	): bool
	{
		if (isset($this->collection[$key])) {
			return TRUE;
		}

		return FALSE;
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
