<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping\Tokenizer;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-tokenizer.html
 */
class Pattern implements \Spameri\ElasticQuery\Mapping\TokenizerInterface
{

	public function getType(): string
	{
		return 'pattern';
	}

}