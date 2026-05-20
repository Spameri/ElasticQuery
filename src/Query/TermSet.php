<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-set-query.html
 */
class TermSet implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $terms
	 */
	public function __construct(
		private string $field,
		private array $terms,
		private string|null $minimumShouldMatchField = null,
		private string|null $minimumShouldMatchScript = null,
	)
	{
		if ($terms === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'TermSet query must contain at least one term.',
			);
		}

		if ($minimumShouldMatchField === null && $minimumShouldMatchScript === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'TermSet query requires either minimumShouldMatchField or minimumShouldMatchScript.',
			);
		}
	}


	public function key(): string
	{
		return 'terms_set_' . $this->field . '_' . \implode('-', $this->terms);
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'terms' => $this->terms,
		];

		if ($this->minimumShouldMatchField !== null) {
			$body['minimum_should_match_field'] = $this->minimumShouldMatchField;
		}

		if ($this->minimumShouldMatchScript !== null) {
			$body['minimum_should_match_script'] = ['source' => $this->minimumShouldMatchScript];
		}

		return [
			'terms_set' => [
				$this->field => $body,
			],
		];
	}

}
