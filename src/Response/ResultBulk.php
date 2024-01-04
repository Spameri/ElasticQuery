<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response;


class ResultBulk implements ResultInterface
{

	private \Spameri\ElasticQuery\Response\Stats $stats;

	public function __construct(
		Stats $stats,
		private \Spameri\ElasticQuery\Response\Result\BulkActionCollection $bulkActionCollection,
	)
	{
		$this->stats = $stats;
	}


	public function stats(): \Spameri\ElasticQuery\Response\Stats
	{
		return $this->stats;
	}


	public function getFirstAction(
		string $id,
	): \Spameri\ElasticQuery\Response\Result\BulkAction
	{
		/** @var \Spameri\ElasticQuery\Response\Result\BulkAction $bulkIAction */
		foreach ($this->bulkActionCollection as $bulkIAction) {
			if ($bulkIAction->id() === $id) {
				return $bulkIAction;
			}
		}

		throw new \Spameri\ElasticQuery\Exception\BulkActionNotFound(
			'Action with id: ' . $id . 'not found.',
		);
	}

}
