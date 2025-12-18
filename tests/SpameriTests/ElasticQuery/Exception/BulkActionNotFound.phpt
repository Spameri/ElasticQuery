<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Exception;

require_once __DIR__ . '/../../bootstrap.php';


class BulkActionNotFound extends \Tester\TestCase
{

	public function testExtendsInvalidArgumentException(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\BulkActionNotFound();

		\Tester\Assert::type(\InvalidArgumentException::class, $exception);
	}


	public function testWithMessage(): void
	{
		$message = 'Bulk action at position 5 not found';
		$exception = new \Spameri\ElasticQuery\Exception\BulkActionNotFound($message);

		\Tester\Assert::same($message, $exception->getMessage());
	}


	public function testWithCode(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\BulkActionNotFound('message', 404);

		\Tester\Assert::same(404, $exception->getCode());
	}


	public function testWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new \Spameri\ElasticQuery\Exception\BulkActionNotFound('message', 0, $previous);

		\Tester\Assert::same($previous, $exception->getPrevious());
	}


	public function testCanBeThrown(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				throw new \Spameri\ElasticQuery\Exception\BulkActionNotFound('Bulk action not found');
			},
			\Spameri\ElasticQuery\Exception\BulkActionNotFound::class,
			'Bulk action not found',
		);
	}


	public function testCanBeCaughtAsInvalidArgumentException(): void
	{
		$caught = false;
		try {
			throw new \Spameri\ElasticQuery\Exception\BulkActionNotFound('test');
		} catch (\InvalidArgumentException $e) {
			$caught = true;
		}

		\Tester\Assert::true($caught);
	}


	public function testEmptyMessage(): void
	{
		$exception = new \Spameri\ElasticQuery\Exception\BulkActionNotFound();

		\Tester\Assert::same('', $exception->getMessage());
	}

}

(new BulkActionNotFound())->run();
