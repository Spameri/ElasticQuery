<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Tokenizer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-letter-tokenizer.html
 */
class Letter implements \Spameri\ElasticQuery\Mapping\TokenizerInterface
{

	public function getType(): string
	{
		return 'letter';
	}


	public function toArray(): array
	{
		return [
			$this->getType()
		];
	}


	public function key(): string
	{
		return $this->getType();
	}

}
