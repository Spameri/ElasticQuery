<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


/**
 * Reusable script value object — emits the inner script body that ES expects
 * inside aggregations, queries, and runtime mappings.
 */
class Script implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @param array<string, mixed> $params
	 */
	public function __construct(
		private string $source,
		private string $lang = 'painless',
		private array $params = [],
	)
	{
	}


	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		$array = [
			'source' => $this->source,
			'lang' => $this->lang,
		];

		if ($this->params !== []) {
			$array['params'] = $this->params;
		}

		return $array;
	}

}
