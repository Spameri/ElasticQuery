<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Entity;


interface EntityInterface extends ArrayInterface
{

	public function key() : string;

}
