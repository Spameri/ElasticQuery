.PHONY: composer
.PHONY: phpstan
.PHONY: cs
.PHONY: tests
.PHONY: coverage

composer:
	composer update --no-interaction --no-suggest --no-progress --prefer-dist --prefer-stable

phpstan:
	vendor/bin/phpstan analyse -l 7 -c phpstan.neon src tests

cs:
	vendor/bin/phpcs --standard=ruleset.xml --cache=$HOME/phpcs-cache/.phpcs-cache src tests

tests:
	vendor/bin/tester -s -p php --colors 1 -C tests

coverage:
	vendor/bin/tester -s -p php --colors 1 -C --coverage ./coverage.html --coverage-src ./src tests
