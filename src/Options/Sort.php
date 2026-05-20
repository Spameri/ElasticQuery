<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html
 */
class Sort implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public const ASC = 'ASC';
	public const DESC = 'DESC';
	public const MISSING_LAST = '_last';
	public const MISSING_FIRST = '_first';

	public function __construct(
		public string $field,
		public string $type = self::DESC,
		public string $missing = self::MISSING_LAST,
		public string|null $mode = null,
		public \Spameri\ElasticQuery\Options\NestedSort|null $nested = null,
		public string|null $numericType = null,
		public string|null $unmappedType = null,
		public string|null $format = null,
	)
	{
		if ( ! \in_array($type, [self::ASC, self::DESC], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting type ' . $type . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
			);
		}
		if ( ! \in_array($missing, [self::MISSING_FIRST, self::MISSING_LAST], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting by missing value on filed ' . $field . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
			);
		}

	}


	public function key(): string
	{
		return $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'order' => $this->type,
			'missing' => $this->missing,
		];

		if ($this->mode !== null) {
			$body['mode'] = $this->mode;
		}

		if ($this->nested !== null) {
			$body['nested'] = $this->nested->toArray();
		}

		if ($this->numericType !== null) {
			$body['numeric_type'] = $this->numericType;
		}

		if ($this->unmappedType !== null) {
			$body['unmapped_type'] = $this->unmappedType;
		}

		if ($this->format !== null) {
			$body['format'] = $this->format;
		}

		return [
			$this->field => $body,
		];
	}

}
