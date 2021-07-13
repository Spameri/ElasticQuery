<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction;

class FieldValueFactor implements \Spameri\ElasticQuery\FunctionScore\FunctionScoreInterface
{

	public const MODIFIER_NONE = 'none';
	public const MODIFIER_LOG = 'log';
	public const MODIFIER_LOG1P = 'log1p';
	public const MODIFIER_LOG2P = 'log2p';
	public const MODIFIER_LN = 'ln';
	public const MODIFIER_LN1P = 'ln1p';
	public const MODIFIER_LN2P = 'ln2p';
	public const MODIFIER_SQUARE = 'square';
	public const MODIFIER_SQRT = 'sqrt';
	public const MODIFIER_RECIPROCAL = 'reciprocal';

	private string $field;

	private float $factor;

	private string $modifier;

	private float $missing;


	public function __construct(
		string $field,
		float $factor = 1.0,
		string $modifier = self::MODIFIER_NONE,
		float $missing = 1.0
	) {
		$this->field = $field;
		$this->factor = $factor;
		$this->modifier = $modifier;
		$this->missing = $missing;
	}


	public function key(): string
	{
		return 'field_value_factor_' . $this->field;
	}


	public function field(): string
	{
		return $this->field;
	}


	public function factor(): float
	{
		return $this->factor;
	}


	public function modifier(): string
	{
		return $this->modifier;
	}


	public function missing(): float
	{
		return $this->missing;
	}


	public function toArray(): array
	{
		return [
			'field_value_factor' => [
				'field' => $this->field,
				'factor' => $this->factor,
				'modifier' => $this->modifier,
				'missing' => $this->missing,
			],
		];
	}

}
