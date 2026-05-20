<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay;


/**
 * Shared base for gauss / linear / exp decay functions.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-decay
 */
abstract class AbstractDecay implements \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
{

	public function __construct(
		protected string $field,
		protected float|int|string|array $origin,
		protected float|int|string $scale,
		protected float|int|string|null $offset = null,
		protected float|null $decay = null,
		protected string|null $multiValueMode = null,
	)
	{
	}


	abstract protected function name(): string;


	public function key(): string
	{
		return $this->name() . '_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$fieldBody = [
			'origin' => $this->origin,
			'scale' => $this->scale,
		];

		if ($this->offset !== null) {
			$fieldBody['offset'] = $this->offset;
		}

		if ($this->decay !== null) {
			$fieldBody['decay'] = $this->decay;
		}

		$body = [$this->field => $fieldBody];

		if ($this->multiValueMode !== null) {
			$body['multi_value_mode'] = $this->multiValueMode;
		}

		return [$this->name() => $body];
	}

}
