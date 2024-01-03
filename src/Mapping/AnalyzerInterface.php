<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping;


interface AnalyzerInterface extends \Spameri\ElasticQuery\Entity\ArrayInterface
{

	public function name(): string;


	public function getType(): string;

}
