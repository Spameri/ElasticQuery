<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class Range implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private float|\DateTimeInterface|int|string|null $gte = null,
		private float|\DateTimeInterface|int|string|null $lte = null,
		private float $boost = 1.0,
		private float|\DateTimeInterface|int|string|null $gt = null,
		private float|\DateTimeInterface|int|string|null $lt = null,
		private string|null $format = null,
		private string|null $relation = null,
		private string|null $timeZone = null,
	)
	{
		if ($gte === null && $lte === null && $gt === null && $lt === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Range must have at least one border value.',
			);
		}

		if ($lte && $gte && $lte < $gte) {
			$this->throwInvalidRange('gte', $gte, 'lte', $lte);
		}

		if ($lt && $gt && $lt < $gt) {
			$this->throwInvalidRange('gt', $gt, 'lt', $lt);
		}

		if ($relation !== null && ! \in_array($relation, \Spameri\ElasticQuery\Query\Range\Relation::RELATIONS, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Range relation ' . $relation . ' is invalid, see \Spameri\ElasticQuery\Query\Range\Relation::RELATIONS.',
			);
		}
	}


	private function throwInvalidRange(
		string $fromKey,
		float|\DateTimeInterface|int|string $from,
		string $toKey,
		float|\DateTimeInterface|int|string $to,
	): never
	{
		$fromValue = $from instanceof \DateTimeInterface ? $from->format('U') : $from;
		$toValue = $to instanceof \DateTimeInterface ? $to->format('U') : $to;

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
			'Input values do not make a range. ' . $fromKey . ': ' . $fromValue . ' ' . $toKey . ': ' . $toValue,
		);
	}


	public function key(): string
	{
		$parts = [];
		foreach (['gte' => $this->gte, 'lte' => $this->lte, 'gt' => $this->gt, 'lt' => $this->lt] as $name => $value) {
			if ($value === null) {
				continue;
			}
			$parts[] = $name . ':' . ($value instanceof \DateTimeInterface ? $value->format('Y-m-d H:i:s') : $value);
		}

		return 'range_' . $this->field . '_' . \implode('_', $parts);
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = ['boost' => $this->boost];

		foreach (['gte' => $this->gte, 'lte' => $this->lte, 'gt' => $this->gt, 'lt' => $this->lt] as $name => $value) {
			if ($value === null) {
				continue;
			}
			$body[$name] = $value instanceof \DateTimeInterface
				? $value->format('Y-m-d H:i:s')
				: $value;
		}

		if ($this->format !== null) {
			$body['format'] = $this->format;
		}

		if ($this->relation !== null) {
			$body['relation'] = $this->relation;
		}

		if ($this->timeZone !== null) {
			$body['time_zone'] = $this->timeZone;
		}

		return [
			'range' => [
				$this->field => $body,
			],
		];
	}

}
