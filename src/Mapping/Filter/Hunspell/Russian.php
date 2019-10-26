<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Russian extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'ru_RU';
	}


	public function getName() : string
	{
		return 'dictionary_RU';
	}

}
