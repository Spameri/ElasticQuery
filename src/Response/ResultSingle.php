<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultSingle implements ResultInterface
{


	public function __construct(
		private \Spameri\ElasticQuery\Response\Result\Hit $hit,
		private StatsSingle $stats,
	)
	{
	}


	public function hit(): \Spameri\ElasticQuery\Response\Result\Hit
	{
		return $this->hit;
	}


	public function stats(): \Spameri\ElasticQuery\Response\StatsSingle
	{
		return $this->stats;
	}

}
