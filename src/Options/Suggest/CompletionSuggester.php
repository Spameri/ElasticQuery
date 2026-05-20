<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options\Suggest;


class CompletionSuggester implements SuggesterInterface
{

	/**
	 * @param array<string, mixed>|null $fuzzy
	 * @param array<string, mixed>|null $regex
	 * @param array<string, mixed>|null $contexts
	 */
	public function __construct(
		private string $name,
		private string $prefix,
		private string $field,
		private int|null $size = null,
		private bool|null $skipDuplicates = null,
		private array|null $fuzzy = null,
		private array|null $regex = null,
		private array|null $contexts = null,
	)
	{
	}


	public function key(): string
	{
		return $this->name;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$body = ['field' => $this->field];

		if ($this->size !== null) {
			$body['size'] = $this->size;
		}
		if ($this->skipDuplicates !== null) {
			$body['skip_duplicates'] = $this->skipDuplicates;
		}
		if ($this->fuzzy !== null) {
			$body['fuzzy'] = $this->fuzzy;
		}
		if ($this->regex !== null) {
			$body['regex'] = $this->regex;
		}
		if ($this->contexts !== null) {
			$body['contexts'] = $this->contexts;
		}

		return [
			'prefix' => $this->prefix,
			'completion' => $body,
		];
	}

}
