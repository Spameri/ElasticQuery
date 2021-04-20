<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Catalan extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'ca';
	}


	public function getName(): string
	{
		return 'dictionary_CA';
	}

}
