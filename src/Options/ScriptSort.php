<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html#script-based-sorting
 */
readonly class ScriptSort implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public const TYPE_NUMBER = 'number';
	public const TYPE_STRING = 'string';

	public function __construct(
		public \Spameri\ElasticQuery\Script $script,
		public string $type = self::TYPE_NUMBER,
		public string $order = Sort::ASC,
		public string|null $mode = null,
		public \Spameri\ElasticQuery\Options\NestedSort|null $nested = null,
	)
	{
		if ( ! \in_array($type, [self::TYPE_NUMBER, self::TYPE_STRING], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'ScriptSort type must be \'number\' or \'string\'.',
			);
		}
		if ( ! \in_array($order, [Sort::ASC, Sort::DESC], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'ScriptSort order must be ASC or DESC.',
			);
		}
	}


	public function key(): string
	{
		return 'script_sort';
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'type' => $this->type,
			'script' => $this->script->toArray(),
			'order' => $this->order,
		];

		if ($this->mode !== null) {
			$body['mode'] = $this->mode;
		}

		if ($this->nested !== null) {
			$body['nested'] = $this->nested->toArray();
		}

		return ['_script' => $body];
	}

}
