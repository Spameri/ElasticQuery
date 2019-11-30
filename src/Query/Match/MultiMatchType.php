<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query\Match;


class MultiMatchType
{

	public const BEST_FIELDS 	= 'best_fields';
	public const MOST_FIELDS 	= 'most_fields';
	public const CROSS_FIELDS 	= 'cross_fields';
	public const PHRASE 		= 'phrase';
	public const PHRASE_PREFIX 	= 'phrase_prefix';
	public const BOOL_PREFIX 	= 'bool_prefix';


	public const TYPES = [
		self::BEST_FIELDS 	=> self::BEST_FIELDS,
		self::MOST_FIELDS 	=> self::MOST_FIELDS,
		self::CROSS_FIELDS 	=> self::CROSS_FIELDS,
		self::PHRASE 		=> self::PHRASE,
		self::PHRASE_PREFIX => self::PHRASE_PREFIX,
		self::BOOL_PREFIX 	=> self::BOOL_PREFIX,
	];

}
