<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Collection;


interface SimpleCollectionInterface extends \IteratorAggregate
{

	/**
	 * @phpstan-param mixed $item
	 */
	public function add(
		$item,
	): void;


	public function remove(
		string $key,
	): bool;


	/**
	 * @phpstan-return mixed
	 */
	public function get(
		string $key,
	);


	public function isValue(
		string $key,
	): bool;


	public function count(): int;


	public function keys(): array;


	public function clear(): void;

}
