<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class Document implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function __construct(
		public string|null $index,
		public \Spameri\ElasticQuery\Document\BodyInterface|null $body = null,
		public string|null $id = null,
		public array $options = [],
	)
	{
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

		if (
			\is_string($this->id)
			&& $this->id !== ''
		) {
			$array['id'] = $this->id;
		}

		if ($this->options) {
			$array = \array_merge($array, $this->options);
		}

		return $array;
	}

}
