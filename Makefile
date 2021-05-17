.PHONY: composer
.PHONY: phpstan
.PHONY: cs
.PHONY: cs-local
.PHONY: tests
.PHONY: coverage

composer:
	composer update --no-interaction --no-suggest --no-progress --prefer-dist --prefer-stable

phpstan:
	vendor/bin/phpstan analyse -l 7 -c phpstan.neon src tests

cs:
	vendor/bin/phpcs --standard=ruleset.xml --cache=.phpcs-cache src tests/SpameriTests

cs-local:
	vendor/bin/phpcs --standard=ruleset.xml src tests/SpameriTests

cbf:
	vendor/bin/phpcbf --standard=ruleset.xml src tests/SpameriTests

tests:
	vendor/bin/tester -s -p php --colors 1 -C -j 1 tests

coverage:
	vendor/bin/tester -s -p php --colors 1 -C --coverage ./coverage.html --coverage-src ./src tests
