<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-script-query.html
 */
class Script implements \Spameri\ElasticQuery\Query\LeafQueryInterface
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


	public function key(): string
	{
		return 'script_' . \md5($this->source);
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$script = [
			'source' => $this->source,
			'lang' => $this->lang,
		];

		if ($this->params !== []) {
			$script['params'] = $this->params;
		}

		return [
			'script' => [
				'script' => $script,
			],
		];
	}

}
