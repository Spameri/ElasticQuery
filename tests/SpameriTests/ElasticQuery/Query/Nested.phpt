<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Nested extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_nested';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayBasic(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('comments');

		$array = $nested->toArray();

		\Tester\Assert::true(isset($array['nested']));
		\Tester\Assert::same('comments', $array['nested']['path']);
		\Tester\Assert::true(isset($array['nested']['query']['bool']));
	}


	public function testToArrayWithQuery(): void
	{
		$queryCollection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$queryCollection->addMustQuery(
			new \Spameri\ElasticQuery\Query\Term('comments.author', 'John'),
		);

		$nested = new \Spameri\ElasticQuery\Query\Nested('comments', $queryCollection);

		$array = $nested->toArray();

		\Tester\Assert::same('comments', $array['nested']['path']);
		\Tester\Assert::true(isset($array['nested']['query']['bool']['bool']['must']));
		\Tester\Assert::count(1, $array['nested']['query']['bool']['bool']['must']);
	}


	public function testGetQuery(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('products');

		$query = $nested->getQuery();

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\QueryCollection::class, $query);
	}


	public function testAddQueryToNested(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('items');
		$nested->getQuery()->addMustQuery(
			new \Spameri\ElasticQuery\Query\Term('items.name', 'Widget'),
		);
		$nested->getQuery()->addShouldQuery(
			new \Spameri\ElasticQuery\Query\Range('items.price', 10, 100),
		);

		$array = $nested->toArray();

		\Tester\Assert::true(isset($array['nested']['query']['bool']['bool']['must']));
		\Tester\Assert::true(isset($array['nested']['query']['bool']['bool']['should']));
	}


	public function testKey(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('reviews');

		\Tester\Assert::same('nested_reviews', $nested->key());
	}


	public function testDeepNestedPath(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('products.variants.sizes');

		$array = $nested->toArray();

		\Tester\Assert::same('products.variants.sizes', $array['nested']['path']);
	}


	public function testNestedInElasticQuery(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('comments');
		$nested->getQuery()->addMustQuery(
			new \Spameri\ElasticQuery\Query\Term('comments.author', 'John'),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					$nested,
				),
			),
		);

		$array = $elasticQuery->toArray();

		\Tester\Assert::true(isset($array['query']['bool']['must']));
		\Tester\Assert::count(1, $array['query']['bool']['must']);
		\Tester\Assert::true(isset($array['query']['bool']['must'][0]['nested']));
		\Tester\Assert::same('comments', $array['query']['bool']['must'][0]['nested']['path']);
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

(new Nested())->run();
