<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class PhrasePrefix extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_phrase_prefix';


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
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			'title',
			'quick brown f',
		);

		$array = $phrasePrefix->toArray();

		\Tester\Assert::true(isset($array['match_phrase_prefix']));
		\Tester\Assert::true(isset($array['match_phrase_prefix']['title']));
		\Tester\Assert::same('quick brown f', $array['match_phrase_prefix']['title']['query']);
		\Tester\Assert::same(1, $array['match_phrase_prefix']['title']['boost']);
		\Tester\Assert::same(1, $array['match_phrase_prefix']['title']['slop']);
	}


	public function testToArrayWithCustomBoostAndSlop(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			'description',
			'search phrase',
			2,
			3,
		);

		$array = $phrasePrefix->toArray();

		\Tester\Assert::same('search phrase', $array['match_phrase_prefix']['description']['query']);
		\Tester\Assert::same(2, $array['match_phrase_prefix']['description']['boost']);
		\Tester\Assert::same(3, $array['match_phrase_prefix']['description']['slop']);
	}


	public function testKey(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			'title',
			'test query',
		);

		\Tester\Assert::same('phrase_prefix_title_test query', $phrasePrefix->key());
	}


	public function testCreate(): void
	{
		$phrasePrefix = new \Spameri\ElasticQuery\Query\PhrasePrefix(
			'title',
			'Aveng',
		);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						null,
						new \Spameri\ElasticQuery\Query\MustCollection(
							$phrasePrefix,
						),
					),
				)
				)->toArray(),
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

(new PhrasePrefix())->run();
