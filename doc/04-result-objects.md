# Result objects

- Every object is as close to array response as possible. But as typed object.
- Every response object implements `\Spameri\ElasticQuery\Response\ResultInterface`

## Implementations
##### Result Single
- Class `\Spameri\ElasticQuery\Response\ResultSingle`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultSingle.php)

##### Result Search
- Class `\Spameri\ElasticQuery\Response\ResultSearch`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultSearch.php)
- [Sample usage](https://github.com/Spameri/ElasticQuery/blob/master/tests/SpameriTests/ElasticQuery/Response/Result.phpt#L76)

##### Result Bulk
- Class `\Spameri\ElasticQuery\Response\ResultBulk`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultBulk.php)
