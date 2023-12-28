<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Hit
{

	/**
	 * @var array
	 */
	private $source;
	/**
	 * @var int
	 */
	private $position;
	/**
	 * @var string
	 */
	private $index;
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var float
	 */
	private $score;
	/**
	 * @var int
	 */
	private $version;


	public function __construct(
		array $source
		, int $position
		, string $index
		, string $type
		, string $id
		, float $score
		, int $version,
	)
	{
		$this->source = $source;
		$this->position = $position;
		$this->index = $index;
		$this->type = $type;
		$this->id = $id;
		$this->score = $score;
		$this->version = $version;
	}


	public function source(): array
	{
		return $this->source;
	}


	/**
	 * @phpstan-return mixed
	 */
	public function getValue(
		string $key,
	)
	{
		$value = $this->getSubValue($key);

		if ($value !== NULL) {
			return $value;
		}

		return $this->source[$key] ?? NULL;
	}


	public function getStringValue(string $key): string
	{
		if (
			isset($this->source[$key])
			&& \is_string($this->source[$key]) === TRUE
		) {
			return $this->source[$key];
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not string.', $key));
	}


	public function getStringOrNullValue(string $key): string|null
	{
		try {
			return $this->getStringValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return NULL;
		}
	}


	public function getArrayValue(string $key): array
	{
		if (
			isset($this->source[$key])
			&& \is_array($this->source[$key]) === TRUE
		) {
			return $this->source[$key];
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not array.', $key));
	}


	public function getArrayOrNullValue(string $key): array|null
	{
		try {
			return $this->getArrayValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return NULL;
		}
	}


	public function getBoolValue(string $key): bool
	{
		if (
			isset($this->source[$key])
			&& \is_bool($this->source[$key]) === TRUE
		) {
			return $this->source[$key];
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not bool.', $key));
	}


	public function getBoolOrNullValue(string $key): bool|null
	{
		try {
			return $this->getBoolValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return NULL;
		}
	}


	public function getIntegerValue(string $key): int
	{
		if (
			isset($this->source[$key])
			&& \is_int($this->source[$key]) === TRUE
		) {
			return $this->source[$key];
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not integer.', $key));
	}


	public function getIntegerOrNullValue(string $key): int|null
	{
		try {
			return $this->getIntegerValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return NULL;
		}
	}


	public function getFloatValue(string $key): float
	{
		if (
			isset($this->source[$key])
			&& \is_float($this->source[$key]) === TRUE
		) {
			return $this->source[$key];
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not float.', $key));
	}


	public function getFloatOrNullValue(string $key): float|null
	{
		try {
			return $this->getFloatValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return NULL;
		}
	}


	/**
	 * @phpstan-return mixed
	 */
	public function getSubValue($key)
	{
		if (\str_contains($key, \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldSeparator::FIELD_SEPARATOR) === TRUE) {
			$levels = \explode(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldSeparator::FIELD_SEPARATOR, $key);

			$value = $this->source[$levels[0]] ?? NULL;
			unset($levels[0]);

			foreach ($levels as $subKey) {
				$value = $value[$subKey] ?? NULL;

				if ($value !== NULL) {
					return $value;
				}
			}
		}

		return NULL;
	}


	public function position(): int
	{
		return $this->position;
	}


	public function index(): string
	{
		return $this->index;
	}


	public function type(): string
	{
		return $this->type;
	}


	public function id(): string
	{
		return $this->id;
	}


	public function score(): float
	{
		return $this->score;
	}


	public function version(): int
	{
		return $this->version;
	}

}
