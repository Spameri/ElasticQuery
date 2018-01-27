<?php declare(strict_types = 1);

//xdebug . var_display_max_children = 256
//xdebug . var_display_max_data = 1024
ini_set('xdebug.var_display_max_depth', '10');

include_once __DIR__ . '/../src/Entity/ArrayInterface.php';
include_once __DIR__ . '/../src/Entity/EntityInterface.php';
include_once __DIR__ . '/../src/Entity/AbstractEntity.php';
include_once __DIR__ . '/../src/Collection/CollectionInterface.php';
include_once __DIR__ . '/../src/Collection/AbstractCollection.php';
include_once __DIR__ . '/../src/Query/Match/Operator.php';
include_once __DIR__ . '/../src/Query/AbstractLeafQuery.php';
include_once __DIR__ . '/../src/ElasticQuery.php';
include_once __DIR__ . '/../src/Query/QueryCollection.php';
include_once __DIR__ . '/../src/Query/MustCollection.php';
include_once __DIR__ . '/../src/Query/Match.php';
include_once __DIR__ . '/../src/Query/Term.php';
include_once __DIR__ . '/../src/Query/Terms.php';
include_once __DIR__ . '/../src/Query/ShouldCollection.php';


$query = new \Spameri\ElasticQuery\ElasticQuery(
	new \Spameri\ElasticQuery\Query\QueryCollection(
		new \Spameri\ElasticQuery\Query\MustCollection( ... [
			new \Spameri\ElasticQuery\Query\Match(
				'name',
				'Jack'
			),
			new \Spameri\ElasticQuery\Query\Term(
				'surname',
				'Oneill'
			),
			new \Spameri\ElasticQuery\Query\QueryCollection(
				NULL,
				new \Spameri\ElasticQuery\Query\ShouldCollection(
					...
					[
						new \Spameri\ElasticQuery\Query\Terms(
							'episode',
							[1,2,3,4,5,]
						)
					]
				)
			)
		]),
		new \Spameri\ElasticQuery\Query\ShouldCollection()
	),
	'',
	''
);


var_dump($query->toArray());


