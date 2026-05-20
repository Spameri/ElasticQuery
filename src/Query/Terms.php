<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class Terms implements LeafQueryInterface
{

	/**
	 * @param array<int, scalar>|\Spameri\ElasticQuery\Query\TermsLookup $query Either inline values or a terms_lookup.
	 */
	public function __construct(
		private string $field,
		private array|\Spameri\ElasticQuery\Query\TermsLookup $query,
		private float $boost = 1.0,
	)
	{
		if (\is_array($query) && \count($query) === 0) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Terms query must contain values, empty array given.',
			);
		}

	}


	public function key(): string
	{
		if ($this->query instanceof \Spameri\ElasticQuery\Query\TermsLookup) {
			return 'terms_' . $this->field . '_lookup';
		}

		return 'terms_' . $this->field . '_' . \implode('-', \array_map('\strval', $this->query));
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		if ($this->query instanceof \Spameri\ElasticQuery\Query\TermsLookup) {
			return [
				'terms' => [
					$this->field => $this->query->toArray(),
					'boost' => $this->boost,
				],
			];
		}

		return [
			'terms' => [
				$this->field => $this->query,
				'boost' => $this->boost,
			],
		];
	}

}
