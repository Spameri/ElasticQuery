<?php

declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

class VersionCheck
{

	/**
	 * @var \Spameri\ElasticQuery\Response\ResultMapper
	 */
	private static $resultMapper;


	public static function check(): \Spameri\ElasticQuery\Response\ResultVersion
	{
		if (self::$resultMapper === NULL) {
			self::$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
		}

		/** @var \Spameri\ElasticQuery\Response\ResultVersion $resultObject */
		$resultObject = self::$resultMapper->map(
			\json_decode(
				(string) \file_get_contents('http://127.0.0.1:9200'),
				TRUE,
			),
		);

		return $resultObject;
	}

}
