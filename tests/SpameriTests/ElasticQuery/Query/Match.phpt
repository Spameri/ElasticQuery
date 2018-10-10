<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Match extends \Tester\TestCase
{

	public function testCreate() : void
	{
		$match = new \Spameri\ElasticQuery\Query\Match(
			'name',
			'Avengers'
		);

		$array = $match->toArray();

		\Tester\Assert::true(isset($array['match']['name']['query']));
		\Tester\Assert::same('Avengers', $array['match']['name']['query']);
	}

}

(new Match())->run();
