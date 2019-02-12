<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class HitCollection implements \IteratorAggregate
{

	/**
	 * @var \Spameri\ElasticQuery\Response\Result\Hit[]
	 */
	private $hits;


	public function __construct(
		\Spameri\ElasticQuery\Response\Result\Hit ... $hits
	)
	{
		$this->hits = $hits;
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->hits);
	}

}
