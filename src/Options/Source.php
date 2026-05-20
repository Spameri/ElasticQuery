<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * _source filtering: false, includes/excludes.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/source-filtering.html
 */
class Source
{

	/**
	 * @param array<int, string>|null $includes
	 * @param array<int, string>|null $excludes
	 */
	public function __construct(
		private bool|null $enabled = null,
		private array|null $includes = null,
		private array|null $excludes = null,
	)
	{
	}


	/**
	 * @return bool|array<int|string, mixed>
	 */
	public function value(): bool|array
	{
		if ($this->enabled === false) {
			return false;
		}

		if ($this->includes === null && $this->excludes === null) {
			return true;
		}

		$array = [];
		if ($this->includes !== null) {
			$array['includes'] = $this->includes;
		}
		if ($this->excludes !== null) {
			$array['excludes'] = $this->excludes;
		}

		return $array;
	}

}
