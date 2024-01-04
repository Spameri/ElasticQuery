<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Version
{

	// 7 digits MAJOR|MAJOR|MINOR|MINOR|PATCH|PATCH
	public const ELASTIC_VERSION_ID_2 = 20000;
	public const ELASTIC_VERSION_ID_24 = 20400;
	public const ELASTIC_VERSION_ID_5 = 50000;
	public const ELASTIC_VERSION_ID_51 = 50100;
	public const ELASTIC_VERSION_ID_52 = 50200;
	public const ELASTIC_VERSION_ID_53 = 50300;
	public const ELASTIC_VERSION_ID_54 = 50400;
	public const ELASTIC_VERSION_ID_55 = 50500;
	public const ELASTIC_VERSION_ID_56 = 50600;
	public const ELASTIC_VERSION_ID_6 = 60000;
	public const ELASTIC_VERSION_ID_61 = 60100;
	public const ELASTIC_VERSION_ID_62 = 60200;
	public const ELASTIC_VERSION_ID_63 = 60300;
	public const ELASTIC_VERSION_ID_64 = 60400;
	public const ELASTIC_VERSION_ID_65 = 60500;
	public const ELASTIC_VERSION_ID_66 = 60600;
	public const ELASTIC_VERSION_ID_67 = 60700;
	public const ELASTIC_VERSION_ID_7 = 70000;
	public const ELASTIC_VERSION_ID_8 = 80000;

	private int $id;

	public function __construct(
		private string $number,
		private string $buildHash,
		private bool $buildSnapshot,
		private string $luceneVersion,
		private string|null $buildFlavor = null,
		private string|null $buildType = null,
		private string|null $buildDate = null,
		private string|null $minimumWireCompatibility = null,
		private string|null $minimumIndexCompatibility = null,
	)
	{
		$this->id = $this->convertVersionNumber($number);
	}


	public function convertVersionNumber(
		string $number,
	): int
	{
		$exploded = \explode('.', $number);

		$major = (int) $exploded[0];
		$version = $major * 10000;

		$minor = (int) $exploded[1];
		$version += $minor * 100;

		$patch = (int) $exploded[2];
		// phpcs:ignore SlevomatCodingStandard.Variables.UselessVariable
		$version += $patch;

		return $version;
	}


	public function number(): string
	{
		return $this->number;
	}


	public function id(): int
	{
		return $this->id;
	}


	public function buildFlavor(): string|null
	{
		return $this->buildFlavor;
	}


	public function buildType(): string|null
	{
		return $this->buildType;
	}


	public function buildHash(): string
	{
		return $this->buildHash;
	}


	public function buildDate(): string|null
	{
		return $this->buildDate;
	}


	public function buildSnapshot(): bool
	{
		return $this->buildSnapshot;
	}


	public function luceneVersion(): string
	{
		return $this->luceneVersion;
	}


	public function minimumWireCompatibility(): string|null
	{
		return $this->minimumWireCompatibility;
	}


	public function minimumIndexCompatibility(): string|null
	{
		return $this->minimumIndexCompatibility;
	}

}
