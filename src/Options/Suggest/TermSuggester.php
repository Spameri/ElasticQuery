<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options\Suggest;


class TermSuggester implements SuggesterInterface
{

	public function __construct(
		private string $name,
		private string $text,
		private string $field,
		private int|null $size = null,
		private string|null $sort = null,
		private string|null $suggestMode = null,
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

		if ($this->sort !== null) {
			$body['sort'] = $this->sort;
		}

		if ($this->suggestMode !== null) {
			$body['suggest_mode'] = $this->suggestMode;
		}

		return [
			'text' => $this->text,
			'term' => $body,
		];
	}

}
