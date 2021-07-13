<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Norwegian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'no';
	}


	public function getName(): string
	{
		return 'dictionary_NO';
	}

}
