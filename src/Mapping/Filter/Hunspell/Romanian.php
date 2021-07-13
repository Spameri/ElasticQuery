<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Romanian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'ro';
	}


	public function getName(): string
	{
		return 'dictionary_RO';
	}

}
