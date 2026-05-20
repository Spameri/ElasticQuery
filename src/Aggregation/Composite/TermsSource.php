<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Composite;


class TermsSource implements CompositeSourceInterface
{

	public function __construct(
		private string $name,
		private string $field,
		private string|null $order = null,
		private bool|null $missingBucket = null,
		private string|null $missingOrder = null,
	)
	{
	}


	public function key(): string
	{
		return $this->name;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = ['field' => $this->field];

		if ($this->order !== null) {
			$body['order'] = $this->order;
		}

		if ($this->missingBucket !== null) {
			$body['missing_bucket'] = $this->missingBucket;
		}

		if ($this->missingOrder !== null) {
			$body['missing_order'] = $this->missingOrder;
		}

		return ['terms' => $body];
	}

}
