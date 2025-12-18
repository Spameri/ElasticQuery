<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Exception;

require_once __DIR__ . '/../../bootstrap.php';


class InvalidArgumentException extends \Tester\TestCase
{

	public function testExtendsPhpInvalidArgumentException(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\InvalidArgumentException();

		\Tester\Assert::type(\InvalidArgumentException::class, $exception);
	}


	public function testWithMessage(): void
	{
		$message = 'Invalid argument provided';
		$exception = new \Spameri\ElasticQuery\Exception\InvalidArgumentException($message);

		\Tester\Assert::same($message, $exception->getMessage());
	}


	public function testWithCode(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\InvalidArgumentException('message', 400);

		\Tester\Assert::same(400, $exception->getCode());
	}


	public function testWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new \Spameri\ElasticQuery\Exception\InvalidArgumentException('message', 0, $previous);

		\Tester\Assert::same($previous, $exception->getPrevious());
	}


	public function testCanBeThrown(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException('Invalid argument');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
			'Invalid argument',
		);
	}


	public function testCanBeCaughtAsPhpInvalidArgumentException(): void
	{
		$caught = false;
		try {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException('test');
		} catch (\InvalidArgumentException $e) {
			$caught = true;
		}

		\Tester\Assert::true($caught);
	}


	public function testEmptyMessage(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\InvalidArgumentException();

		\Tester\Assert::same('', $exception->getMessage());
	}


	public function testIsDistinctFromPhpException(): void
	{
		$phpException = new \InvalidArgumentException('test');
		$libraryException = new \Spameri\ElasticQuery\Exception\InvalidArgumentException('test');

		\Tester\Assert::type(\Spameri\ElasticQuery\Exception\InvalidArgumentException::class, $libraryException);
		\Tester\Assert::false($phpException instanceof \Spameri\ElasticQuery\Exception\InvalidArgumentException);
	}

}

(new InvalidArgumentException())->run();
