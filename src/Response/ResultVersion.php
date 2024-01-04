<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultVersion implements ResultInterface
{

	public function __construct(
		private string $name,
		private string $clusterName,
		private string $clusterUUID,
		private \Spameri\ElasticQuery\Response\Result\Version $version,
		private string $tagLine,
	)
	{
	}


	public function name(): string
	{
		return $this->name;
	}


	public function clusterName(): string
	{
		return $this->clusterName;
	}


	public function clusterUUID(): string
	{
		return $this->clusterUUID;
	}


	public function version(): \Spameri\ElasticQuery\Response\Result\Version
	{
		return $this->version;
	}


	public function tagLine(): string
	{
		return $this->tagLine;
	}

}
