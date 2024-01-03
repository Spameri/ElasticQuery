<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Tokenizer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-ngram-tokenizer.html
 */
class NGram implements \Spameri\ElasticQuery\Mapping\TokenizerInterface
{

	public function getType(): string
	{
		return 'ngram';
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
