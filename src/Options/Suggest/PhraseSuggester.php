<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options\Suggest;


class PhraseSuggester implements SuggesterInterface
{

	public function __construct(
		private string $name,
		private string $text,
		private string $field,
		private int|null $size = null,
		private int|null $gramSize = null,
		private float|null $confidence = null,
		private float|null $maxErrors = null,
		private string|null $separator = null,
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
		if ($this->gramSize !== null) {
			$body['gram_size'] = $this->gramSize;
		}
		if ($this->confidence !== null) {
			$body['confidence'] = $this->confidence;
		}
		if ($this->maxErrors !== null) {
			$body['max_errors'] = $this->maxErrors;
		}
		if ($this->separator !== null) {
			$body['separator'] = $this->separator;
		}

		return [
			'text' => $this->text,
			'phrase' => $body,
		];
	}

}
