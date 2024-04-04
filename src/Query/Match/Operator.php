<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query\Match;


class Operator
{

	public const AND = 'AND';
	public const OR = 'OR';

	public const OPERATORS = [
		self::AND => self::AND,
		self::OR => self::OR,
	];

}
