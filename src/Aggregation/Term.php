<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
class Term implements LeafAggregationInterface
{

	private string $field;

	private int $size;

	private ?int $missing;

	private ?string $key;

	private \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection $order;

	private ?string $include;

	private ?string $exclude;


	public function __construct(
		string $field,
		int $size = 0,
		int $missing = NULL,
		?\Spameri\ElasticQuery\Aggregation\Terms\OrderCollection $order = NULL,
		?string $include = NULL,
		?string $exclude = NULL,
		?string $key = NULL
	)
	{
		$this->field = $field;
		$this->size = $size;
		$this->missing = $missing;
		$this->key = $key;
		$this->order = $order ?? new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();
		$this->include = $include;
		$this->exclude = $exclude;
	}


	public function key(): string
	{
		return $this->key ?? $this->field;
	}


	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->size > 0) {
			$array['size']  = $this->size;
		}

		if ($this->missing !== NULL) {
			$array['missing'] = $this->missing;
		}

		if (\count($this->order)) {
			$array['order'] = $this->order->toArray();
		}

		if ($this->include !== NULL) {
			$array['include'] = $this->include;
		}

		if ($this->exclude !== NULL) {
			$array['exclude'] = $this->exclude;
		}

		return [
			'terms' => $array,
		];
	}

}
