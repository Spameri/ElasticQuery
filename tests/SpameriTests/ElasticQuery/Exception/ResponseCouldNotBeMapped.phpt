<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Exception;

require_once __DIR__ . '/../../bootstrap.php';


class ResponseCouldNotBeMapped extends \Tester\TestCase
{

	public function testExtendsInvalidArgumentException(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('test');

		\Tester\Assert::type(\InvalidArgumentException::class, $exception);
	}


	public function testMessageIsJsonEncoded(): void
	{
		$message = 'Response mapping failed';
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped($message);

		\Tester\Assert::same(\json_encode($message), $exception->getMessage());
	}


	public function testWithCode(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('message', 500);

		\Tester\Assert::same(500, $exception->getCode());
	}


	public function testWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('message', 0, $previous);

		\Tester\Assert::same($previous, $exception->getPrevious());
	}


	public function testCanBeThrown(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				throw new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('Mapping failed');
			},
			\Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped::class,
		);
	}


	public function testCanBeCaughtAsInvalidArgumentException(): void
	{
		$caught = false;
		try {
			throw new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('test');
		} catch (\InvalidArgumentException $e) {
			$caught = true;
		}

		\Tester\Assert::true($caught);
	}


	public function testSpecialCharactersInMessage(): void
	{
		$message = 'Error with "quotes" and special chars: <>&';
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped($message);

		\Tester\Assert::same(\json_encode($message), $exception->getMessage());
	}


	public function testUnicodeInMessage(): void
	{
		$message = 'Chyba mapování odpovědi: žluťoučký kůň';
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped($message);

		\Tester\Assert::same(\json_encode($message), $exception->getMessage());
	}


	public function testEmptyMessage(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped('');

		\Tester\Assert::same('""', $exception->getMessage());
	}


	public function testMessageWithNewlines(): void
	{
		$message = "Line1\nLine2\nLine3";
		$exception = new \Spameri\ElasticQuery\Exception\ResponseCouldNotBeMapped($message);

		\Tester\Assert::same(\json_encode($message), $exception->getMessage());
	}

}

(new ResponseCouldNotBeMapped())->run();
