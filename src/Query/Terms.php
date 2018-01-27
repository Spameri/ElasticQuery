<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


class Terms extends AbstractLeafQuery
{

	/**
	 * @var string
	 */
	private $field;
	/**
	 * @var array
	 */
	private $query;
	/**
	 * @var float
	 */
	private $boost;


	public function __construct(
		string $field,
		array $query,
		float $boost = 1.0
	)
	{
		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
	}


	public function key() : string
	{
		return $this->field . '_' . $this->query;
	}


	public function toArray() : array
	{
		$array = [
			'terms' => [
				$this->field 	=> $this->query,
				'boost' 		=> $this->boost,
			],
		];

		return $array;
	}
}
