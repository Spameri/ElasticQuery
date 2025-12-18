<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Tokenizer;

require_once __DIR__ . '/../../../bootstrap.php';


class NGram extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram();

		\Tester\Assert::same('ngram', $tokenizer->getType());
	}


	public function testKey(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram();

		\Tester\Assert::same('ngram', $tokenizer->key());
	}


	public function testToArray(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram();

		$expected = ['ngram'];

		\Tester\Assert::same($expected, $tokenizer->toArray());
	}


	public function testImplementsTokenizerInterface(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
	}

}

(new NGram())->run();
