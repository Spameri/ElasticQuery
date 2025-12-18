<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Tokenizer;

require_once __DIR__ . '/../../../bootstrap.php';


class Pattern extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Pattern();

		\Tester\Assert::same('pattern', $tokenizer->getType());
	}


	public function testKey(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Pattern();

		\Tester\Assert::same('pattern', $tokenizer->key());
	}


	public function testToArray(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Pattern();

		$expected = ['pattern'];

		\Tester\Assert::same($expected, $tokenizer->toArray());
	}


	public function testImplementsTokenizerInterface(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Pattern();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
	}

}

(new Pattern())->run();
