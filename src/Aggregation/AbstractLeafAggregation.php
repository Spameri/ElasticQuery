<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


abstract class AbstractLeafAggregation extends \Spameri\ElasticQuery\Entity\AbstractEntity
{

	abstract public function name() : string ;
}
