<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class MultiMatch extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_video_multi_match';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}


	public function testToArrayBasic(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'search term',
		);

		$array = $multiMatch->toArray();

		\Tester\Assert::true(isset($array['multi_match']));
		\Tester\Assert::same('search term', $array['multi_match']['query']);
		\Tester\Assert::same(['title', 'description'], $array['multi_match']['fields']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS, $array['multi_match']['type']);
		\Tester\Assert::same(1.0, $array['multi_match']['boost']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Operator::OR, $array['multi_match']['operator']);
	}


	public function testToArrayWithAllOptions(): void
	{
		$fuzziness = new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO');
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title^2', 'description', 'content^0.5'],
			'search term',
			2.0,
			$fuzziness,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::CROSS_FIELDS,
			2,
			\Spameri\ElasticQuery\Query\Match\Operator::AND,
			'standard',
		);

		$array = $multiMatch->toArray();

		\Tester\Assert::same('search term', $array['multi_match']['query']);
		\Tester\Assert::same(['title^2', 'description', 'content^0.5'], $array['multi_match']['fields']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\MultiMatchType::CROSS_FIELDS, $array['multi_match']['type']);
		\Tester\Assert::same(2.0, $array['multi_match']['boost']);
		\Tester\Assert::same(\Spameri\ElasticQuery\Query\Match\Operator::AND, $array['multi_match']['operator']);
		\Tester\Assert::same('AUTO', $array['multi_match']['fuzziness']);
		\Tester\Assert::same('standard', $array['multi_match']['analyzer']);
		\Tester\Assert::same(2, $array['multi_match']['minimum_should_match']);
	}


	public function testKey(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'test query',
		);

		\Tester\Assert::same('multiMatch_title-description_test query', $multiMatch->key());
	}


	public function testChangeAnalyzer(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title'],
			'test',
		);

		$multiMatch->changeAnalyzer('custom_analyzer');
		$array = $multiMatch->toArray();

		\Tester\Assert::same('custom_analyzer', $array['multi_match']['analyzer']);
	}


	public function testInvalidOperatorThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\MultiMatch(
					['title'],
					'test',
					1.0,
					null,
					\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
					null,
					'INVALID',
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testInvalidTypeThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\MultiMatch(
					['title'],
					'test',
					1.0,
					null,
					'invalid_type',
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testAllMultiMatchTypes(): void
	{
		$types = [
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::MOST_FIELDS,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::CROSS_FIELDS,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::PHRASE,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::PHRASE_PREFIX,
			\Spameri\ElasticQuery\Query\Match\MultiMatchType::BOOL_PREFIX,
		];

		foreach ($types as $type) {
			$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
				['title'],
				'test',
				1.0,
				null,
				$type,
			);
			$array = $multiMatch->toArray();
			\Tester\Assert::same($type, $array['multi_match']['type']);
		}
	}


	public function testCreate(): void
	{
		$multiMatch = new \Spameri\ElasticQuery\Query\MultiMatch(
			['title', 'description'],
			'Avengers',
		);

		$document = new \Spameri\ElasticQuery\Document(
			self::INDEX,
			new \Spameri\ElasticQuery\Document\Body\Plain(
				(
				new \Spameri\ElasticQuery\ElasticQuery(
					new \Spameri\ElasticQuery\Query\QueryCollection(
						null,
						new \Spameri\ElasticQuery\Query\MustCollection(
							$multiMatch,
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

		\curl_close($ch);
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
		\curl_close($ch);
	}

}

(new MultiMatch())->run();
