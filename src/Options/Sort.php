<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html
 */
class Sort implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public const ASC = 'ASC';
	public const DESC = 'DESC';
	public const MISSING_LAST = '_last';
	public const MISSING_FIRST = '_first';

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $missing;


	public function __construct(
		string $field
		, string $type = self::DESC
		, string $missing = self::MISSING_LAST
	)
	{
		if ( ! \in_array($type, [self::ASC, self::DESC], TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting type ' . $type . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.'
			);
		}
		if ( ! \in_array($missing, [self::MISSING_FIRST, self::MISSING_LAST], TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting by missing value on filed ' . $field . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.'
			);
		}

		$this->field = $field;
		$this->type = $type;
		$this->missing = $missing;
	}


	public function key(): string
	{
		return $this->field;
	}


	public function toArray(): array
	{
		return [
			$this->field => [
				'order' => $this->type,
				'missing' => $this->missing,
			],
		];
	}

}
