<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Indonesian extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'id';
	}


	public function getName(): string
	{
		return 'dictionary_ID';
	}

}
