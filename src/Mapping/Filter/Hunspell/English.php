<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class English extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'en_GB';
	}


	public function getName() : string
	{
		return 'dictionary_EN';
	}

}
