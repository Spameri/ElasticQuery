<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Exception;


class ResponseCouldNotBeMapped extends \InvalidArgumentException
{

	public function __construct(
		string $message,
		int $code = 0,
		\Throwable $previous = null,
	)
	{
		parent::__construct((string) \json_encode($message), $code, $previous);
	}

}
