<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * Point-in-time reference for stable pagination.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/point-in-time-api.html
 */
readonly class Pit implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		public string $id,
		public string|null $keepAlive = null,
	)
	{
	}


	/**
	 * @return array<string, string>
	 */
	public function toArray(): array
	{
		$array = ['id' => $this->id];

		if ($this->keepAlive !== null) {
			$array['keep_alive'] = $this->keepAlive;
		}

		return $array;
	}

}
