<?php

declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;

final class ElasticsearchTestCase
{

	public static function getHost(): string
	{
		return \ELASTICSEARCH_HOST;
	}


	public static function getBaseUrl(): string
	{
		return 'http://' . \ELASTICSEARCH_HOST;
	}

}
