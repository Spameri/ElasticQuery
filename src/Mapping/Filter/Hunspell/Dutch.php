<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Dutch extends \Spameri\ElasticQuery\Mapping\Filter\Hunspell
{

	public function getLocale() : string
	{
		return 'nl_NL';
	}


	public function getName() : string
	{
		return 'dictionary_NL';
	}

}
