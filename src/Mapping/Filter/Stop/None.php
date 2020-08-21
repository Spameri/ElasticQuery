<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Stop;

class None extends \Spameri\ElasticQuery\Mapping\Filter\AbstractStop
{

	public function getStopWords() : array
	{
		return [
			\Spameri\ElasticQuery\Mapping\Analyzer\Stop\StopWords::NONE,
		];
	}


	public function getName() : string
	{
		return 'noneStopWords';
	}

}
