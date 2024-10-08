<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Hit
{

	public function __construct(
		private array $source,
		private int $position,
		private string $index,
		private string $type,
		private string $id,
		private float $score,
		private int $version,
	)
	{
	}


	public function source(): array
	{
		return $this->source;
	}


	public function getValue(
		string $key,
	): mixed
	{
		$value = $this->getSubValue($key);

		return $value ?? $this->source[$key] ?? null;
	}


	public function getStringValue(string $key): string
	{
		if (
			\is_string($this->getValue($key)) === true
		) {
			return $this->getValue($key);
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not string.', $key));
	}


	public function getStringOrNullValue(string $key): string|null
	{
		try {
			return $this->getStringValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return null;
		}
	}


	public function getArrayValue(string $key): array
	{
		if (
			\is_array($this->getValue($key)) === true
		) {
			return $this->getValue($key);
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not array.', $key));
	}


	public function getArrayOrNullValue(string $key): array|null
	{
		try {
			return $this->getArrayValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return null;
		}
	}


	public function getBoolValue(string $key): bool
	{
		if (
			\is_bool($this->getValue($key)) === true
		) {
			return $this->getValue($key);
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not bool.', $key));
	}


	public function getBoolOrNullValue(string $key): bool|null
	{
		try {
			return $this->getBoolValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return null;
		}
	}


	public function getIntegerValue(string $key): int
	{
		if (
			\is_int($this->getValue($key)) === true
		) {
			return $this->getValue($key);
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not integer.', $key));
	}


	public function getIntegerOrNullValue(string $key): int|null
	{
		try {
			return $this->getIntegerValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return null;
		}
	}


	public function getFloatValue(string $key): float
	{
		if (
			\is_float($this->getValue($key)) === true
		) {
			return $this->getValue($key);
		}

		throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(\sprintf('Value for key %s is not float.', $key));
	}


	public function getFloatOrNullValue(string $key): float|null
	{
		try {
			return $this->getFloatValue($key);

		} catch (\Spameri\ElasticQuery\Exception\InvalidArgumentException $exception) {
			return null;
		}
	}


	public function getSubValue(string $key): mixed
	{
		if (\str_contains($key, \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldSeparator::FIELD_SEPARATOR) === true) {
			$levels = \explode(\Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldSeparator::FIELD_SEPARATOR, $key);

			$value = $this->source[$levels[0]] ?? null;
			unset($levels[0]);

			foreach ($levels as $subKey) {
				$value = $value[$subKey] ?? null;
			}

			if ($value !== null) {
				return $value;
			}
		}

		return null;
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
