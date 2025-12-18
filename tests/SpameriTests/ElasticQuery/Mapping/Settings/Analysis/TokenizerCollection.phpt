<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Mapping\Settings\Analysis;

require_once __DIR__ . '/../../../../bootstrap.php';


class TokenizerCollection extends \Tester\TestCase
{

	public function testEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testConstructorWithTokenizers(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram(),
		);

		\Tester\Assert::same(2, $collection->count());
	}


	public function testAdd(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram());

		\Tester\Assert::same(1, $collection->count());
		\Tester\Assert::true($collection->isValue('edge_ngram'));
	}


	public function testAddReplacesSameKey(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();

		$collection->add(new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram());
		$collection->add(new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram());

		\Tester\Assert::same(1, $collection->count());
	}


	public function testRemove(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
		);

		$result = $collection->remove('edge_ngram');

		\Tester\Assert::true($result);
		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::false($collection->isValue('edge_ngram'));
	}


	public function testRemoveNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();

		$result = $collection->remove('non_existent');

		\Tester\Assert::false($result);
	}


	public function testGet(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
		);

		$tokenizer = $collection->get('edge_ngram');

		\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
		\Tester\Assert::same('edge_ngram', $tokenizer->key());
	}


	public function testGetNonExistent(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection();

		$tokenizer = $collection->get('non_existent');

		\Tester\Assert::null($tokenizer);
	}


	public function testIsValue(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
		);

		\Tester\Assert::true($collection->isValue('edge_ngram'));
		\Tester\Assert::false($collection->isValue('non_existent'));
	}


	public function testCount(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard(),
		);

		\Tester\Assert::same(3, $collection->count());
	}


	public function testKeys(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram(),
		);

		$keys = $collection->keys();

		\Tester\Assert::count(2, $keys);
		\Tester\Assert::contains('edge_ngram', $keys);
		\Tester\Assert::contains('ngram', $keys);
	}


	public function testClear(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
		);

		$collection->clear();

		\Tester\Assert::same(0, $collection->count());
		\Tester\Assert::same([], $collection->keys());
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram(),
		);

		$iterator = $collection->getIterator();

		\Tester\Assert::type(\ArrayIterator::class, $iterator);
		\Tester\Assert::same(2, $iterator->count());
	}


	public function testForeach(): void
	{
		$collection = new \Spameri\ElasticQuery\Mapping\Settings\Analysis\TokenizerCollection(
			new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(),
			new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram(),
		);

		$keys = [];
		foreach ($collection as $key => $tokenizer) {
			$keys[] = $key;
			\Tester\Assert::type(\Spameri\ElasticQuery\Mapping\TokenizerInterface::class, $tokenizer);
		}

		\Tester\Assert::count(2, $keys);
	}

}

(new TokenizerCollection())->run();
