<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Result;


require_once __DIR__ . '/../../bootstrap.php';


class ResultVersion extends \Tester\TestCase
{

	public function testCreate() : void
	{
		$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
		/** @var \Spameri\ElasticQuery\Response\ResultVersion $resultObject */
		$resultObject = $resultMapper->map(\json_decode(\file_get_contents('http://127.0.0.1:9200'), TRUE));

		\Tester\Assert::true($resultObject instanceof \Spameri\ElasticQuery\Response\ResultVersion);

		if (\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_7 <= $resultObject->version()->id()) {
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_7 < $resultObject->version()->id()
			);
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_8 > $resultObject->version()->id()
			);

		} elseif (\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_6 <= $resultObject->version()->id()) {
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_6 < $resultObject->version()->id()
			);
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_7 > $resultObject->version()->id()
			);

		} elseif (\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_5 <= $resultObject->version()->id()) {
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_5 < $resultObject->version()->id()
			);
			\Tester\Assert::true(
				\Spameri\ElasticQuery\Response\Result\Version::ELASTIC_VERSION_ID_6 > $resultObject->version()->id()
			);

		} else {
			\Tester\Assert::fail('ElasticSearch version did not match supported versions.');
		}
	}

}

(new ResultVersion())->run();
