<?php declare(strict_types = 1);

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
	private $id;

	/**
	 * @var array
	 */
	private $options;


	public function __construct(
		?string $index
		, ?\Spameri\ElasticQuery\Document\BodyInterface $body = NULL
		, ?string $id = NULL
		, array $options = []
	)
	{
		$this->index = $index;
		$this->body = $body;
		$this->id = $id;
		$this->options = $options;
	}


	public function index(): ?string
	{
		return $this->index;
	}


	public function toArray() : array
	{
		$array = [];

		if ($this->index) {
			$array['index'] = $this->index;
		}

		if ($this->body) {
			$array['body'] = $this->body->toArray();
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
