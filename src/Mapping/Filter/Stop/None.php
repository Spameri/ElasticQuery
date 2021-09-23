<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

class None extends \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
{

	public function getStopWords(): array
	{
		$parent = parent::getStopWords();
		$parent[] = \Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::NONE;

		return $parent;
	}


	public function getName(): string
	{
		return 'noneStopWords';
	}

}
