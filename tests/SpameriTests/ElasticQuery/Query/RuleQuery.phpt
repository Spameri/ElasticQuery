<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class RuleQuery extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_rule';


	public function testToArray(): void
	{
		$rule = new \Spameri\ElasticQuery\Query\RuleQuery(
			organic: new \Spameri\ElasticQuery\Query\Term('field', 'value'),
			rulesetIds: ['my-ruleset'],
			matchCriteria: ['query_string' => 'puggles'],
		);

		$array = $rule->toArray();

		\Tester\Assert::same(['my-ruleset'], $array['rule']['ruleset_ids']);
		\Tester\Assert::same(['query_string' => 'puggles'], $array['rule']['match_criteria']);
		\Tester\Assert::same('value', $array['rule']['organic']['term']['field']['value']);
	}


	public function testRequiresRulesetIds(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\RuleQuery(
					organic: new \Spameri\ElasticQuery\Query\Term('a', 'b'),
					rulesetIds: [],
					matchCriteria: [],
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}

}

(new RuleQuery())->run();
