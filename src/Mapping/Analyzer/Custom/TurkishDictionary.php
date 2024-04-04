<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom;

class TurkishDictionary extends \Spameri\ElasticQuery\Mapping\Analyzer\AbstractDictionary
{

	public const NAME = 'turkishDictionary';

	public function name(): string
	{
		return self::NAME;
	}


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	{
		if ( ! $this->filter instanceof \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection) {
			$this->filter = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			);

			if ($this->stopFilter) {
				$this->filter->add($this->stopFilter);

			} else {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\Turkish(),
				);
			}
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Hunspell\Turkish(),
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			);

			if ($this->stopFilter) {
				$this->filter->add($this->stopFilter);

			} else {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\Turkish(),
				);
			}
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Unique(),
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding(),
			);
		}

		return $this->filter;
	}

}
