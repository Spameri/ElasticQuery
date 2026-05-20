<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Pinned extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_pinned';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArray(): void
	{
		$pinned = new \Spameri\ElasticQuery\Query\Pinned(
			organic: new \Spameri\ElasticQuery\Query\ElasticMatch('content', 'elasticsearch'),
			ids: ['1', '4', '100'],
		);

		$array = $pinned->toArray();

		\Tester\Assert::same(['1', '4', '100'], $array['pinned']['ids']);
		\Tester\Assert::true(isset($array['pinned']['organic']['match']));
	}


	public function testRequiresIdsOrDocs(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Pinned(
					new \Spameri\ElasticQuery\Query\MatchAll(),
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$pinned = new \Spameri\ElasticQuery\Query\Pinned(
			organic: new \Spameri\ElasticQuery\Query\MatchAll(),
			ids: ['1'],
		);

		\Tester\Assert::same('pinned_match_all', $pinned->key());
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}

}

(new Pinned())->run();
