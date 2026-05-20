<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * Rule query — applies Search Application query rules over an organic query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-rule-query.html
 */
class RuleQuery implements LeafQueryInterface
{

	/**
	 * @param array<int, string> $rulesetIds
	 * @param array<string, mixed> $matchCriteria
	 */
	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $organic,
		private array $rulesetIds,
		private array $matchCriteria,
	)
	{
		if ($rulesetIds === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'RuleQuery requires at least one rulesetId.',
			);
		}
	}


	public function key(): string
	{
		return 'rule_' . $this->organic->key() . '_' . \implode('-', $this->rulesetIds);
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'rule' => [
				'organic' => $this->organic->toArray(),
				'ruleset_ids' => $this->rulesetIds,
				'match_criteria' => $this->matchCriteria,
			],
		];
	}

}
