<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-percolate-query.html
 */
class Percolate implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed>|null $document Inline document to percolate.
	 */
	public function __construct(
		private string $field,
		private array|null $document = null,
		private string|null $index = null,
		private string|null $id = null,
	)
	{
		if ($document === null && ($index === null || $id === null)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Percolate query requires either a document, or both index and id.',
			);
		}
	}


	public function key(): string
	{
		return 'percolate_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'field' => $this->field,
		];

		if ($this->document !== null) {
			$body['document'] = $this->document;

		} else {
			$body['index'] = $this->index;
			$body['id'] = $this->id;
		}

		return [
			'percolate' => $body,
		];
	}

}
