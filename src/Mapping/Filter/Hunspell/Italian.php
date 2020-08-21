<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Italian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale() : string
	{
		return 'it_IT';
	}


	public function getName() : string
	{
		return 'dictionary_IT';
	}

}
