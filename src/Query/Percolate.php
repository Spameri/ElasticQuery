<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-percolate-query.html
 */
class Percolate implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<string, mixed>|null $document Single inline document to percolate.
	 * @param array<int, array<string, mixed>>|null $documents Multi-doc inline percolation.
	 */
	public function __construct(
		private string $field,
		private array|null $document = null,
		private string|null $index = null,
		private string|null $id = null,
		private array|null $documents = null,
		private string|null $name = null,
		private string|null $routing = null,
		private string|null $preference = null,
		private int|null $version = null,
	)
	{
		if ($document === null && $documents === null && ($index === null || $id === null)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Percolate query requires either a document, documents, or both index and id.',
			);
		}
	}


	public function key(): string
	{
		return 'percolate_' . $this->field . ($this->name !== null ? '_' . $this->name : '');
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'field' => $this->field,
		];

		if ($this->documents !== null) {
			$body['documents'] = $this->documents;

		} elseif ($this->document !== null) {
			$body['document'] = $this->document;

		} else {
			$body['index'] = $this->index;
			$body['id'] = $this->id;
		}

		if ($this->name !== null) {
			$body['name'] = $this->name;
		}

		if ($this->routing !== null) {
			$body['routing'] = $this->routing;
		}

		if ($this->preference !== null) {
			$body['preference'] = $this->preference;
		}

		if ($this->version !== null) {
			$body['version'] = $this->version;
		}

		return [
			'percolate' => $body,
		];
	}

}
