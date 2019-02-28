<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class BulkActionCollection implements \IteratorAggregate
{

	/**
	 * @var array<\Spameri\ElasticQuery\Response\Result\BulkAction>
	 */
	private $bulkActions;


	public function __construct(
		BulkAction ... $bulkActions
	)
	{
		$this->bulkActions = $bulkActions;
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->bulkActions);
	}

}
