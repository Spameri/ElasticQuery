<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

require_once __DIR__ . '/../bootstrap.php';


class Highlight extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<em>'],
			postTags: ['</em>'],
			fields: ['title'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(['<em>'], $array['pre_tags']);
		\Tester\Assert::same(['</em>'], $array['post_tags']);
		\Tester\Assert::true(isset($array['fields']['title']));
	}


	public function testToArrayWithMultipleFields(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<mark>'],
			postTags: ['</mark>'],
			fields: ['title', 'content', 'description'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::true(isset($array['fields']['title']));
		\Tester\Assert::true(isset($array['fields']['content']));
		\Tester\Assert::true(isset($array['fields']['description']));
	}


	public function testToArrayWithMultipleTags(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<strong>', '<em>'],
			postTags: ['</strong>', '</em>'],
			fields: ['body'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(['<strong>', '<em>'], $array['pre_tags']);
		\Tester\Assert::same(['</strong>', '</em>'], $array['post_tags']);
	}


	public function testToArrayFieldsHaveNumberOfFragments(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<b>'],
			postTags: ['</b>'],
			fields: ['summary'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(0, $array['fields']['summary']['number_of_fragments']);
	}


	public function testToArrayAllFieldsHaveNumberOfFragments(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<span class="highlight">'],
			postTags: ['</span>'],
			fields: ['field1', 'field2', 'field3'],
		);

		$array = $highlight->toArray();

		foreach (['field1', 'field2', 'field3'] as $field) {
			\Tester\Assert::same(0, $array['fields'][$field]['number_of_fragments']);
		}
	}


	public function testToArrayWithEmptyFields(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<em>'],
			postTags: ['</em>'],
			fields: [],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(['<em>'], $array['pre_tags']);
		\Tester\Assert::same(['</em>'], $array['post_tags']);
		\Tester\Assert::false(isset($array['fields']));
	}


	public function testToArrayWithEmptyTags(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: [],
			postTags: [],
			fields: ['content'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same([], $array['pre_tags']);
		\Tester\Assert::same([], $array['post_tags']);
	}


	public function testToArrayWithHtmlTags(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<span class="search-highlight" style="background: yellow;">'],
			postTags: ['</span>'],
			fields: ['text'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(['<span class="search-highlight" style="background: yellow;">'], $array['pre_tags']);
	}


	public function testToArrayStructure(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<em>'],
			postTags: ['</em>'],
			fields: ['title', 'body'],
		);

		$array = $highlight->toArray();

		// Verify structure matches Elasticsearch highlight format
		\Tester\Assert::true(\array_key_exists('pre_tags', $array));
		\Tester\Assert::true(\array_key_exists('post_tags', $array));
		\Tester\Assert::true(\array_key_exists('fields', $array));
		\Tester\Assert::true(\is_array($array['fields']));
	}


	public function testImplementsArrayInterface(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<em>'],
			postTags: ['</em>'],
			fields: ['content'],
		);

		\Tester\Assert::type(\Spameri\ElasticQuery\Entity\ArrayInterface::class, $highlight);
	}


	public function testToArrayWithSpecialCharactersInTags(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['[[HIGHLIGHT]]'],
			postTags: ['[[/HIGHLIGHT]]'],
			fields: ['data'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::same(['[[HIGHLIGHT]]'], $array['pre_tags']);
		\Tester\Assert::same(['[[/HIGHLIGHT]]'], $array['post_tags']);
	}


	public function testToArrayWithNestedFieldNames(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<em>'],
			postTags: ['</em>'],
			fields: ['user.name', 'address.city', 'metadata.tags'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::true(isset($array['fields']['user.name']));
		\Tester\Assert::true(isset($array['fields']['address.city']));
		\Tester\Assert::true(isset($array['fields']['metadata.tags']));
	}


	public function testToArrayWithWildcardField(): void
	{
		$highlight = new \Spameri\ElasticQuery\Highlight(
			preTags: ['<mark>'],
			postTags: ['</mark>'],
			fields: ['*'],
		);

		$array = $highlight->toArray();

		\Tester\Assert::true(isset($array['fields']['*']));
		\Tester\Assert::same(0, $array['fields']['*']['number_of_fragments']);
	}

}

(new Highlight())->run();
