<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


class Sort extends \Spameri\ElasticQuery\Entity\AbstractEntity
{
	public const ASC = 'ASC';
	public const DESC = 'DESC';

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
		, string $missing = '_last'
	)
	{
		if ( ! \in_array($type, [self::ASC, self::DESC], TRUE)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting type ' . $type . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.'
			);
		}

		$this->field = $field;
		$this->type = $type;
		$this->missing = $missing;
	}


	public function key() : string
	{
		return $this->field;
	}


	public function toArray() : array
	{
		return [
			$this->field => [
				'order' => $this->type,
				'missing' => $this->missing,
			],
		];
	}

}
