<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Filter\Hunspell;

class Danish extends \Spameri\ElasticQuery\Mapping\Filter\AbstractHunspell
{

	public function getLocale(): string
	{
		return 'da_DK';
	}


	public function getName(): string
	{
		return 'dictionary_DK';
	}

}
