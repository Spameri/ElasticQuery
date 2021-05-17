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

	/**
	 * @var bool
	 */
	private $fromEqual;

	/**
	 * @var bool
	 */
	private $toEqual;


	public function __construct(
		string $key,
		int $from,
		int $to,
		bool $fromEqual = TRUE,
		bool $toEqual = TRUE
	)
	{
		$this->key = $key;
		$this->from = $from;
		$this->to = $to;
		$this->fromEqual = $fromEqual;
		$this->toEqual = $toEqual;
	}


	public function key(): string
	{
		return $this->key;
	}


	public function toArray(): array
	{
		$from = $this->from;
		$to = $this->to;

		if ( ! $this->fromEqual) {
			$from++;
		}
		if ($this->toEqual) {
			$to++;
		}

		return [
			'key'  => $this->key,
			'from' => $from,
			'to' => $to,
		];
	}

}
