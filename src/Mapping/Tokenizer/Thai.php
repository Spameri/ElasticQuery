<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Tokenizer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-thai-tokenizer.html
 */
class Thai implements \Spameri\ElasticQuery\Mapping\TokenizerInterface
{

	public function getType(): string
	{
		return 'thai';
	}


	public function toArray(): array
	{
		return [
			$this->getType(),
		];
	}


	public function key(): string
	{
		return $this->getType();
	}

}
