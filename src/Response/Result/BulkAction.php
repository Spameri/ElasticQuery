<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class BulkAction
{

	/**
	 * @var string
	 */
	private $action;

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
	 * @var int
	 */
	private $version;

	/**
	 * @var string
	 */
	private $result;

	/**
	 * @var \Spameri\ElasticQuery\Response\Shards
	 */
	private $shards;

	/**
	 * @var int
	 */
	private $status;

	/**
	 * @var int
	 */
	private $seqNo;

	/**
	 * @var int
	 */
	private $primaryTerm;


	public function __construct(
		string $action
		, string $index
		, string $type
		, string $id
		, int $version
		, string $result
		, \Spameri\ElasticQuery\Response\Shards $shards
		, int $status
		, int $seqNo
		, int $primaryTerm
	)
	{
		$this->action = $action;
		$this->index = $index;
		$this->type = $type;
		$this->id = $id;
		$this->version = $version;
		$this->result = $result;
		$this->shards = $shards;
		$this->status = $status;
		$this->seqNo = $seqNo;
		$this->primaryTerm = $primaryTerm;
	}


	public function action(): string
	{
		return $this->action;
	}


	public function index(): string
	{
		return $this->index;
	}


	public function type(): string
	{
		return $this->type;
	}


	public function id(): string
	{
		return $this->id;
	}


	public function version(): int
	{
		return $this->version;
	}


	public function result(): string
	{
		return $this->result;
	}


	public function shards(): \Spameri\ElasticQuery\Response\Shards
	{
		return $this->shards;
	}


	public function status(): int
	{
		return $this->status;
	}


	public function seqNo(): int
	{
		return $this->seqNo;
	}


	public function primaryTerm(): int
	{
		return $this->primaryTerm;
	}

}
