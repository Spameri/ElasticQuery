<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Mapping;

interface CustomAnalyzerInterface extends AnalyzerInterface
{

	public function tokenizer(): string;


	public function filter(): \Spameri\ElasticQuery\Mapping\Settings\Analysis\FilterCollection;

}
