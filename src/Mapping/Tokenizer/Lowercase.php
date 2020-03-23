<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Tokenizer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lowercase-tokenizer.html
 */
class Lowercase implements \Spameri\ElasticQuery\Mapping\TokenizerInterface
{

	public function getType(): string
	{
		return 'lowercase';
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
