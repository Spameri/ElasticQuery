<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query\Match;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness
 */
class Fuzziness
{

	public const AUTO = 'AUTO';

	public function __construct(
		private string $fuzziness,
	)
	{
		if ( ! (\strpos($fuzziness, self::AUTO) === 0 || \is_numeric($fuzziness))) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $fuzziness is not in valid pattern see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness',
			);
		}

	}


	public function __toString(): string
	{
		return $this->fuzziness;
	}

}
