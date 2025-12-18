<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Exception;

require_once __DIR__ . '/../../bootstrap.php';


class AggregationNotFound extends \Tester\TestCase
{

	public function testExtendsInvalidArgumentException(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\AggregationNotFound();

		\Tester\Assert::type(\InvalidArgumentException::class, $exception);
	}


	public function testWithMessage(): void
	{
		$message = 'Aggregation "test_agg" not found';
		$exception = new \Spameri\ElasticQuery\Exception\AggregationNotFound($message);

		\Tester\Assert::same($message, $exception->getMessage());
	}


	public function testWithCode(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\AggregationNotFound('message', 404);

		\Tester\Assert::same(404, $exception->getCode());
	}


	public function testWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new \Spameri\ElasticQuery\Exception\AggregationNotFound('message', 0, $previous);

		\Tester\Assert::same($previous, $exception->getPrevious());
	}


	public function testCanBeThrown(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				throw new \Spameri\ElasticQuery\Exception\AggregationNotFound('Aggregation not found');
			},
			\Spameri\ElasticQuery\Exception\AggregationNotFound::class,
			'Aggregation not found',
		);
	}


	public function testCanBeCaughtAsInvalidArgumentException(): void
	{
		$caught = false;
		try {
			throw new \Spameri\ElasticQuery\Exception\AggregationNotFound('test');
		} catch (\InvalidArgumentException $e) {
			$caught = true;
		}

		\Tester\Assert::true($caught);
	}


	public function testEmptyMessage(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\AggregationNotFound();

		\Tester\Assert::same('', $exception->getMessage());
	}

}

(new AggregationNotFound())->run();
