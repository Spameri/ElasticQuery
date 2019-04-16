# Using with [ElasticSearch/ElasticSearch](https://github.com/elastic/elasticsearch-php)
First we need to prepare query for what we want to search.
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();
$query->query()->must()->add(
	new \Spameri\ElasticQuery\Query\Match(
		'name',
		'Avengers'
	)
);
```

`\Spameri\ElasticQuery\ElasticQuery` is base for every query you want to perform. 
- It has must, should sections.
- Every must, should section is collection of `\Spameri\ElasticQuery\Query\LeafQueryInterface` 
item and should, must collection are also implementations of **LeafQueryInterface** so it allows you to nest them as you need.

Next we need to set up document to send to elasticsearch library.
```php
$document = new \Spameri\ElasticQuery\Document(
	'spameri_video',
	new \Spameri\ElasticQuery\Document\Body\Plain($elasticQuery->toArray())
);
```
- Document is very straightforward, you need to specify index in first argument and document body in second argument
third argument is type if needed by you ElasticSearch version, also fourth is ID.

Last you need to send document to ElasticSearch client.
```php
$response = \Elasticsearch\Client::search(
	$document->toArray()
);
```

Elasticsearch library will return array as response. You can map this response to object like this:
```php
$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
$resultObject = $resultMapper->map($response);
```
Object is implementation of `\Spameri\ElasticQuery\Response\ResultInterface` depending on your type of query.

Or you can map result to specific object by calling direct mapping methods.
- For single result you get by searching by ID. (as parameter in document object)
```php
\Spameri\ElasticQuery\Response\ResultMapper::mapSingleResult($response);
```
- For search response with multiple hits.
```php
\Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);
```
- For bulk result, where you have information about bulk actions.
```php
\Spameri\ElasticQuery\Response\ResultMapper::mapBulkResult($response);
```

# Using with Spameri/Elastic
This is implementation for Nette framework, so you need to register it to your application accordingly.
[How to here](https://github.com/Spameri/Elastic/blob/master/doc/01_intro.md#1-config-elasticsearch)

Then you inject client provider where you need and do query like this.
```php
$response = $this->clientProvider->client()->search(
	$document->toArray()
);
```
Response mapping is same, so is constructing query.

For more advanced use see Spameri/Elastic [documentation](https://github.com/Spameri/Elastic/blob/master/doc/01_intro.md)

# Using with Guzzle
Difference is you dont need document object. You have to specify index and type in requested url by yourself. 
```php
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://localhost:9200/spameri_video/_search', [
	'body' => \json_encode($elasticQuery->toArray())
]);
```
Rest is same. Setting up query and mapping to result object.
