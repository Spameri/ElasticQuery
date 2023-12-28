<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery;

class Highlight implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	private array $preTags;

	private array $postTags;

	private array $fields;


	public function __construct(
		array $preTags,
		array $postTags,
		array $fields,
	) {
		$this->preTags = $preTags;
		$this->postTags = $postTags;
		$this->fields = $fields;
	}


	public function toArray(): array
	{
		$array = [
			'pre_tags' => $this->preTags,
			'post_tags' => $this->postTags,
		];

		foreach ($this->fields as $key) {
			$array['fields'][$key] = [
				'number_of_fragments' => 0,
			];
		}

		return $array;
	}

}
