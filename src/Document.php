<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Document implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var ?string
	 */
	private $index;

	/**
	 * @var ?\Spameri\ElasticQuery\Document\BodyInterface
	 */
	private $body;

	/**
	 * @var ?string
	 */
	private $type;

	/**
	 * @var ?string
	 */
	private $id;

	/**
	 * @var array
	 */
	private $options;


	public function __construct(
		string|null $index,
		\Spameri\ElasticQuery\Document\BodyInterface|null $body = NULL,
		string|null $type = NULL,
		string|null $id = NULL,
		array $options = [],
	)
	{
		$this->index = $index;
		$this->body = $body;
		$this->type = $type;
		$this->id = $id;
		$this->options = $options;
	}


	public function index(): string|null
	{
		return $this->index;
	}


	public function type(): string|null
	{
		return $this->type;
	}


	public function toArray(): array
	{
		$array = [];

		if ($this->index) {
			$array['index'] = $this->index;
		}

		if ($this->body) {
			$array['body'] = $this->body->toArray();
		}

		if ($this->type) {
			$array['type'] = $this->type;
		}

		if ($this->id) {
			$array['id'] = $this->id;
		}

		if ($this->options) {
			$array = \array_merge($array, $this->options);
		}

		return $array;
	}

}
