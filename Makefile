.PHONY: it
it: coding-standards tests

.PHONY: code-coverage
code-coverage: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

.PHONY: tests
tests: vendor
	vendor/bin/phpunit --configuration=./phpunit.xml

vendor: composer.json composer.lock
	composer validate
	composer install
	composer normalize


css:
	./scripts/compileScss.sh
