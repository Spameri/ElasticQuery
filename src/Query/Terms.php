<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class Terms implements LeafQueryInterface
{

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var array
	 */
	private $query;

	/**
	 * @var float
	 */
	private $boost;


	public function __construct(
		string $field
		, array $query
		, float $boost = 1.0
	)
	{
		if ( ! \count($query)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Terms query must contain values, empty array given.'
			);
		}

		$this->field = $field;
		$this->query = $query;
		$this->boost = $boost;
	}


	public function key(): string
	{
		return 'terms_' . $this->field . '_' . \implode('-', $this->query);
	}


	public function toArray(): array
	{
		// phpcs:ignore SlevomatCodingStandard.Variables.UselessVariable
		$array = [
			'terms' => [
				$this->field 	=> $this->query,
				'boost' 		=> $this->boost,
			],
		];

		return $array;
	}

}
