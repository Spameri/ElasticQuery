<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Analyzer\Custom\Synonym;

class EnglishSynonym extends \Spameri\ElasticQuery\Mapping\Analyzer\Custom\Synonym\AbstractSynonym
{

	public const NAME = 'englishSynonym';

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
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\English(),
				);
			}

			if (\count($this->synonyms)) {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Synonym(
						$this->synonyms,
					),
				);
			}

			if ($this->filePath !== NULL) {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\FileSynonym(
						$this->filePath,
					),
				);
			}

			$this->filter->add(
				new \Spameri\ElasticQuery\Mapping\Filter\Lowercase(),
			);
			if ($this->stopFilter) {
				$this->filter->add($this->stopFilter);

			} else {
				$this->filter->add(
					new \Spameri\ElasticQuery\Mapping\Filter\Stop\English(),
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
