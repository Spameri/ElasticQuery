<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Exception;


class ResponseCouldNotBeMapped extends \InvalidArgumentException
{

	/**
	 * @param string $message [optional] The Exception message to throw.
	 */
	public function __construct(
		$message,
		int $code = 0,
		\Throwable $previous = NULL,
	)
	{
		parent::__construct((string) \json_encode($message), $code, $previous);
	}

}
