<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Bulgarian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'bg_BG';
	}


	public function getName(): string
	{
		return 'dictionary_BG';
	}

}
