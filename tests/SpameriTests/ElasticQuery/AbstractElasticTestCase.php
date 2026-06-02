<?php

declare(strict_types = 1);

namespace SpameriTests\ElasticQuery;


/**
 * Base test case for round-tripping ElasticQuery objects through a real Elasticsearch instance.
 *
 * Subclasses define INDEX, optionally override mapping(), and use the protected helpers
 * (createIndex, indexDocument, search) instead of duplicating curl boilerplate.
 */
abstract class AbstractElasticTestCase extends \Tester\TestCase
{

	protected const INDEX = 'spameri_elastic_query_test';


	public function setUp(): void
	{
		$this->createIndex($this->mapping());
	}


	public function tearDown(): void
	{
		$this->deleteIndex();
	}


	/**
	 * @return array<string, mixed>|null Override to provide {settings, mappings} for the index.
	 */
	protected function mapping(): array|null
	{
		return null;
	}


	/**
	 * @param array<string, mixed>|null $mapping
	 */
	protected function createIndex(array|null $mapping = null): void
	{
		$this->request('PUT', static::INDEX, $mapping);
	}


	protected function deleteIndex(): void
	{
		$this->request('DELETE', static::INDEX);
	}


	/**
	 * @param array<string, mixed> $body
	 */
	protected function indexDocument(
		array $body,
		string|null $id = null,
		bool $refresh = true,
	): void
	{
		$suffix = $id !== null ? '/_doc/' . \rawurlencode($id) : '/_doc';
		if ($refresh) {
			$suffix .= '?refresh=true';
		}

		$this->request($id !== null ? 'PUT' : 'POST', static::INDEX . $suffix, $body);
	}


	protected function search(
		\Spameri\ElasticQuery\ElasticQuery $query,
	): \Spameri\ElasticQuery\Response\ResultSearch
	{
		$response = $this->request('POST', static::INDEX . '/_search', $query->toArray());

		$result = (new \Spameri\ElasticQuery\Response\ResultMapper())->map($response);
		\assert($result instanceof \Spameri\ElasticQuery\Response\ResultSearch);

		return $result;
	}


	/**
	 * @param array<string, mixed>|null $body
	 * @return array<string, mixed>
	 */
	protected function request(
		string $method,
		string $path,
		array|null $body = null,
	): array
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . $path);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $method);
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		if ($body !== null) {
			\curl_setopt($ch, \CURLOPT_POSTFIELDS, (string) \json_encode($body));
		}

		$response = \curl_exec($ch);

		if ($response === false) {
			return [];
		}

		$decoded = \json_decode((string) $response, true);

		return \is_array($decoded) ? $decoded : [];
	}

}
