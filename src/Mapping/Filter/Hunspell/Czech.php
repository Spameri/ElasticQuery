<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Czech extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'cs_CZ';
	}


	public function getName() : string
	{
		return 'dictionary_CZ';
	}

}
