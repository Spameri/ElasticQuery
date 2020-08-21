<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class German extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale() : string
	{
		return 'de';
	}


	public function getName() : string
	{
		return 'dictionary_DE';
	}

}
