<?php

declare(strict_types = 1);

/**
 * This file is part of the Spameri (http://www.github.com/spameri)
 *
 * Copyright (c) 2018 Václav Čevela (vcevela@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */
$loader = include __DIR__ . '/../../vendor/autoload.php';
if ( ! $loader) {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

// configure environment
\Tester\Environment::setup();
\date_default_timezone_set('Europe/Prague');

// Elasticsearch host configuration (can be overridden via environment variable)
\define('ELASTICSEARCH_HOST', \getenv('ELASTICSEARCH_HOST') ?: 'localhost:9200');

return $loader;
