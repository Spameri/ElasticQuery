<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Thai extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale() : string
	{
		return 'th_TH';
	}


	public function getName() : string
	{
		return 'dictionary_TH';
	}

}
