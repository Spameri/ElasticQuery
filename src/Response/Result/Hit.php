<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Hit
{

	/**
	 * @var array
	 */
	private $source;
	/**
	 * @var int
	 */
	private $position;
	/**
	 * @var string
	 */
	private $index;
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var float
	 */
	private $score;


	public function __construct(
		array $source
		, int $position
		, string $index
		, string $type
		, string $id
		, float $score
	)
	{
		$this->source = $source;
		$this->position = $position;
		$this->index = $index;
		$this->type = $type;
		$this->id = $id;
		$this->score = $score;
	}


	public function source() : array
	{
		return $this->source;
	}


	public function getValue(
		string $key
	)
	{
		return $this->source[$key];
	}


	public function position() : int
	{
		return $this->position;
	}


	public function index() : string
	{
		return $this->index;
	}


	public function type() : string
	{
		return $this->type;
	}


	public function id() : string
	{
		return $this->id;
	}


	public function score() : float
	{
		return $this->score;
	}

}
