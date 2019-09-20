<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Latvian extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'lv_LV';
	}


	public function getName() : string
	{
		return 'dictionary_LV';
	}

}
