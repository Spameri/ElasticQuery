<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query\Range;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class Relation
{

	public const INTERSECTS = 'INTERSECTS';
	public const CONTAINS = 'CONTAINS';
	public const WITHIN = 'WITHIN';

	public const RELATIONS = [
		self::INTERSECTS => self::INTERSECTS,
		self::CONTAINS => self::CONTAINS,
		self::WITHIN => self::WITHIN,
	];

}
