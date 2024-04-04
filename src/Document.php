<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Document implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		private string|null $index,
		private \Spameri\ElasticQuery\Document\BodyInterface|null $body = null,
		private string|null $type = null,
		private string|null $id = null,
		private array $options = [],
	)
	{
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
