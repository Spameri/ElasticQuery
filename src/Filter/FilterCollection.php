<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Filter;


class FilterCollection implements FilterInterface
{

	private \Spameri\ElasticQuery\Query\MustCollection $mustCollection;

	private \Spameri\ElasticQuery\Query\ShouldCollection $shouldCollection;

	private \Spameri\ElasticQuery\Query\MustNotCollection $mustNotCollection;

	private \Spameri\ElasticQuery\Query\MustCollection $filterCollection;


	public function __construct(
		\Spameri\ElasticQuery\Query\MustCollection|null $mustCollection = null,
		\Spameri\ElasticQuery\Query\ShouldCollection|null $shouldCollection = null,
		\Spameri\ElasticQuery\Query\MustNotCollection|null $mustNotCollection = null,
		\Spameri\ElasticQuery\Query\MustCollection|null $filterCollection = null,
	)
	{
		$this->mustCollection = $mustCollection ?? new \Spameri\ElasticQuery\Query\MustCollection();
		$this->shouldCollection = $shouldCollection ?? new \Spameri\ElasticQuery\Query\ShouldCollection();
		$this->mustNotCollection = $mustNotCollection ?? new \Spameri\ElasticQuery\Query\MustNotCollection();
		$this->filterCollection = $filterCollection ?? new \Spameri\ElasticQuery\Query\MustCollection();
	}


	public function must(): \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->mustCollection;
	}


	public function should(): \Spameri\ElasticQuery\Query\ShouldCollection
	{
		return $this->shouldCollection;
	}


	public function mustNot(): \Spameri\ElasticQuery\Query\MustNotCollection
	{
		return $this->mustNotCollection;
	}


	public function filter(): \Spameri\ElasticQuery\Query\MustCollection
	{
		return $this->filterCollection;
	}


	public function key(): string
	{
		return '';
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$bool = [];

		foreach ($this->mustCollection as $item) {
			$bool['must'][] = $item->toArray();
		}

		foreach ($this->shouldCollection as $item) {
			$bool['should'][] = $item->toArray();
		}

		foreach ($this->mustNotCollection as $item) {
			$bool['must_not'][] = $item->toArray();
		}

		foreach ($this->filterCollection as $item) {
			$bool['filter'][] = $item->toArray();
		}

		if ($bool === []) {
			return [];
		}

		return ['bool' => $bool];
	}

}
