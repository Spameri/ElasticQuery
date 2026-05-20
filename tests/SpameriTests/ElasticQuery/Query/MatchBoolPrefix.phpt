<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MatchBoolPrefix extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_match_bool_prefix';


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
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix(
			field: 'message',
			query: 'quick brown f',
		);

		$array = $match->toArray();

		\Tester\Assert::same('quick brown f', $array['match_bool_prefix']['message']['query']);
	}


	public function testKey(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix('message', 'quick');

		\Tester\Assert::same('match_bool_prefix_message_quick', $match->key());
	}


	public function testCreate(): void
	{
		$match = new \Spameri\ElasticQuery\Query\MatchBoolPrefix('message', 'q');

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						null,
						new \Spameri\ElasticQuery\Query\MustCollection($match),
					),
				))->toArray(),
			),
		);

		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . $document->index . '/_search');
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'GET');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		\curl_setopt(
			$ch,
			\CURLOPT_POSTFIELDS,
			\json_encode($document->toArray()['body']),
		);

		\Tester\Assert::noError(static function () use ($ch): void {
			$response = \curl_exec($ch);
			$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
			/** @var \Spameri\ElasticQuery\Response\ResultSearch $result */
			$result = $resultMapper->map(\json_decode($response, true));
			\Tester\Assert::type('int', $result->stats()->total());
		});
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

(new MatchBoolPrefix())->run();
