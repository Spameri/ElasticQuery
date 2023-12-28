<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Document;


class Bulk implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var array
	 */
	private $data;


	public function __construct(
		array $data,
	)
	{
		$this->data = $data;
	}


	public function toArray(): array
	{
		return [
			'body' => $this->data,
		];
	}

}
