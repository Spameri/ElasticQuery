<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Tokenizer;

require_once __DIR__ . '/../../../bootstrap.php';


class EdgeNGram extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram();

		\Tester\Assert::same('edge_ngram', $tokenizer->getType());
	}


	public function testKey(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram();

		\Tester\Assert::same('edge_ngram', $tokenizer->key());
	}


	public function testToArray(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram();

		$expected = ['edge_ngram'];

		\Tester\Assert::same($expected, $tokenizer->toArray());
	}


	public function testImplementsTokenizerInterface(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
	}

}

(new EdgeNGram())->run();
