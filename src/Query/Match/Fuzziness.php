<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query\Match;


class Fuzziness
{

	/**
	 * @var string
	 */
	private $fuzziness;


	public function __construct(
		string $fuzziness
	)
	{
		if ( ! (\strpos($fuzziness, 'AUTO') === 0 || \is_numeric($fuzziness))) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $fuzziness is not in valid pattern see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness'
			);
		}

		$this->fuzziness = $fuzziness;
	}


	public function __toString() : string
	{
		return $this->fuzziness;
	}
}
