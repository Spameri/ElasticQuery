<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultVersion implements ResultInterface
{

	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $clusterName;
	/**
	 * @var string
	 */
	private $clusterUUID;
	/**
	 * @var \Spameri\ElasticQuery\Response\Result\Version
	 */
	private $version;
	/**
	 * @var string
	 */
	private $tagLine;


	public function __construct(
		string $name,
		string $clusterName,
		string $clusterUUID,
		\Spameri\ElasticQuery\Response\Result\Version $version,
		string $tagLine
	)
	{
		$this->name = $name;
		$this->clusterName = $clusterName;
		$this->clusterUUID = $clusterUUID;
		$this->version = $version;
		$this->tagLine = $tagLine;
	}


	public function name() : string
	{
		return $this->name;
	}


	public function clusterName() : string
	{
		return $this->clusterName;
	}


	public function clusterUUID() : string
	{
		return $this->clusterUUID;
	}


	public function version() : \Spameri\ElasticQuery\Response\Result\Version
	{
		return $this->version;
	}


	public function tagLine() : string
	{
		return $this->tagLine;
	}

}
