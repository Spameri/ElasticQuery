<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Portuguese extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale() : string
	{
		return 'pt_PT';
	}


	public function getName() : string
	{
		return 'dictionary_PT';
	}

}
