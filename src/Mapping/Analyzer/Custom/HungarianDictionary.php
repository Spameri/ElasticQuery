<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom;

class HungarianDictionary extends \Spameri\ElasticQuery\Mapping\Analyzer\AbstractDictionary
{

	public function name(): string
	{
		return 'hungarianDictionary';
	}


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection
	{
		if ( ! $this->filter instanceof \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection) {
			$this->filter = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection();
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Stop\Hungarian()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Hunspell\Hungarian()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Stop\Hungarian()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Unique()
			);
			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\ASCIIFolding()
			);
		}

		return $this->filter;
	}

}
