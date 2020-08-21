<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Hindi extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale() : string
	{
		return 'hi_IN';
	}


	public function getName() : string
	{
		return 'dictionary_IN';
	}

}
