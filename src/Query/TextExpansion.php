<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Text expansion query (legacy ELSER form, superseded by sparse_vector in 8.15+).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-text-expansion-query.html
 */
class TextExpansion implements LeafQueryInterface
{

	/**
	 * @param array<string, mixed>|null $pruningConfig
	 */
	public function __construct(
		private string $field,
		private string $modelId,
		private string $modelText,
		private array|null $pruningConfig = null,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'text_expansion_' . $this->field;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'model_id' => $this->modelId,
			'model_text' => $this->modelText,
			'boost' => $this->boost,
		];

		if ($this->pruningConfig !== null) {
			$body['pruning_config'] = $this->pruningConfig;
		}

		return [
			'text_expansion' => [
				$this->field => $body,
			],
		];
	}

}
