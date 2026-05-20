<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Highlight;


class HighlightFieldCollection extends \Spameri\ElasticQuery\Collection\AbstractCollection implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [];
		foreach ($this->collection as $field) {
			\assert($field instanceof HighlightField);
			$array[$field->key()] = $field->toArray();
		}

		return $array;
	}

}
