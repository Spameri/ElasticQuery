<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Tokenizer;

require_once __DIR__ . '/../../../bootstrap.php';


class Standard extends \Tester\TestCase
{

	public function testGetType(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard();

		\Tester\Assert::same('standard', $tokenizer->getType());
	}


	public function testKey(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard();

		\Tester\Assert::same('standard', $tokenizer->key());
	}


	public function testToArray(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard();

		$expected = ['standard'];

		\Tester\Assert::same($expected, $tokenizer->toArray());
	}


	public function testImplementsTokenizerInterface(): void
	{
		$tokenizer = new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard();

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
	}

}

(new Standard())->run();
