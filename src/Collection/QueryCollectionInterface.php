<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Collection;


interface QueryCollectionInterface extends \IteratorAggregate
{

	public function add(
		\Spameri\ElasticQuery\Query\LeafQueryInterface $item,
	): void;


	public function remove(
		string $key,
	): bool;


	public function get(
		string $key,
	): \Spameri\ElasticQuery\Query\LeafQueryInterface|null;


	public function isValue(
		string $key,
	): bool;


	public function count(): int;


	public function keys(): array;


	public function clear(): void;

}
