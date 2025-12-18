<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Exception;

require_once __DIR__ . '/../../bootstrap.php';


class HitNotFound extends \Tester\TestCase
{

	public function testExtendsInvalidArgumentException(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\HitNotFound();

		\Tester\Assert::type(\InvalidArgumentException::class, $exception);
	}


	public function testWithMessage(): void
	{
		$message = 'Hit with ID "abc123" not found';
		$exception = new \Spameri\ElasticQuery\Exception\HitNotFound($message);

		\Tester\Assert::same($message, $exception->getMessage());
	}


	public function testWithCode(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\HitNotFound('message', 404);

		\Tester\Assert::same(404, $exception->getCode());
	}


	public function testWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new \Spameri\ElasticQuery\Exception\HitNotFound('message', 0, $previous);

		\Tester\Assert::same($previous, $exception->getPrevious());
	}


	public function testCanBeThrown(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				throw new \Spameri\ElasticQuery\Exception\HitNotFound('Hit not found');
			},
			\Spameri\ElasticQuery\Exception\HitNotFound::class,
			'Hit not found',
		);
	}


	public function testCanBeCaughtAsInvalidArgumentException(): void
	{
		$caught = false;
		try {
			throw new \Spameri\ElasticQuery\Exception\HitNotFound('test');
		} catch (\InvalidArgumentException $e) {
			$caught = true;
		}

		\Tester\Assert::true($caught);
	}


	public function testEmptyMessage(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\HitNotFound();

		\Tester\Assert::same('', $exception->getMessage());
	}

}

(new HitNotFound())->run();
