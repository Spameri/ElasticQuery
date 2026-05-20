<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Semantic extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_semantic';


	public function testToArray(): void
	{
		$semantic = new \Spameri\ElasticQuery\Query\Semantic(
			field: 'inference_field',
			query: 'large cat',
			boost: 1.5,
		);

		$array = $semantic->toArray();

		\Tester\Assert::same('inference_field', $array['semantic']['field']);
		\Tester\Assert::same('large cat', $array['semantic']['query']);
		\Tester\Assert::same(1.5, $array['semantic']['boost']);
	}


	public function testKey(): void
	{
		$semantic = new \Spameri\ElasticQuery\Query\Semantic('f', 'q');

		\Tester\Assert::same('semantic_f', $semantic->key());
	}

}

(new Semantic())->run();
