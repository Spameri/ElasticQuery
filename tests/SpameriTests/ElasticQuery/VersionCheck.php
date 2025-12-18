<?php

declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

class VersionCheck
{

	private static \Spameri\ElasticQuery\Response\ResultMapper|null $resultMapper = null;


	public static function check(): \Spameri\ElasticQuery\Response\ResultVersion
	{
		if (self::$resultMapper === null) {
			self::$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
		}

		/** @var \Spameri\ElasticQuery\Response\ResultVersion $resultObject */
		$resultObject = self::$resultMapper->map(
			\json_decode(
				(string) \file_get_contents(ElasticsearchTestCase::getBaseUrl()),
				true,
			),
		);

		return $resultObject;
	}

}
