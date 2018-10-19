<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class RangeValue implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var int
	 */
	private $from;

	/**
	 * @var int
	 */
	private $to;


	public function __construct(
		string $key
		, int $from
		, int $to
	)
	{
		$this->key = $key;
		$this->from = $from;
		$this->to = $to;
	}


	public function key() : string
	{
		return $this->key;
	}


	public function toArray() : array
	{
		return [
			'key'  => $this->key,
			'from' => $this->from,
			'to'   => $this->to,
		];
	}

}
