<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction;

class Weight implements \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
{

	private float $weight;

	private \Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery;


	public function __construct(
		float $weight,
		\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery,
	) {
		$this->weight = $weight;
		$this->leafQuery = $leafQuery;
	}


	public function key(): string
	{
		return 'weight_' . $this->leafQuery->key();
	}


	public function weight(): float
	{
		return $this->weight;
	}


	public function leafQuery(): \Spameri\ElasticQuery\Query\LeafQueryInterface
	{
		return $this->leafQuery;
	}


	public function toArray(): array
	{
		return [
			'weight' => $this->weight,
			'filter' => $this->leafQuery->toArray(),
		];
	}

}
