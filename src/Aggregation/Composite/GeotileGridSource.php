<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Composite;


class GeotileGridSource implements CompositeSourceInterface
{

	public function __construct(
		private string $name,
		private string $field,
		private int|null $precision = null,
		private string|null $order = null,
		private bool|null $missingBucket = null,
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

		if ($this->precision !== null) {
			$body['precision'] = $this->precision;
		}

		if ($this->order !== null) {
			$body['order'] = $this->order;
		}

		if ($this->missingBucket !== null) {
			$body['missing_bucket'] = $this->missingBucket;
		}

		return ['geotile_grid' => $body];
	}

}
