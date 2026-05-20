<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\FunctionScore\ScoreFunction\Decay;


class Linear extends AbstractDecay
{

	protected function name(): string
	{
		return 'linear';
	}

}
