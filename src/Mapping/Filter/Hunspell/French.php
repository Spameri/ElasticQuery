<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class French extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'fr_FR';
	}


	public function getName() : string
	{
		return 'dictionary_FR';
	}

}
