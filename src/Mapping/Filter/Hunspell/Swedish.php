<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Swedish extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'sv_SE';
	}


	public function getName(): string
	{
		return 'dictionary_SE';
	}

}
