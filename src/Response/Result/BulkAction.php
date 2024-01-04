<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class BulkAction
{

	public function __construct(
		private string $action,
		private string $index,
		private string $type,
		private string $id,
		private int $version,
		private string $result,
		private \Spameri\ElasticQuery\Response\Shards $shards,
		private int $status,
		private int $seqNo,
		private int $primaryTerm,
	)
	{
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
