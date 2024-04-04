<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Turkish extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'tr_TR';
	}


	public function getName(): string
	{
		return 'dictionary_TR';
	}

}
