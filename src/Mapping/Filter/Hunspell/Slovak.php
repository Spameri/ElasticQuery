<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Slovak extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'sk_SK';
	}


	public function getName(): string
	{
		return 'dictionary_SK';
	}

}
