export ROOT_DIR=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

all: test testrace

up:
	symfony server:stop
	docker compose up -d
	symfony server:start -d --no-tls
	symfony run -d --watch=config,src,templates,vendor symfony
	symfony run -d yarn encore dev-server --port 9001
	symfony server:log

compose-down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

.PHONY: lint
lint:
	vendor/bin/parallel-lint src

.PHONY: ecs
ecs:
	vendor/bin/ecs check src --config reports/config/ecs.php
.PHONY: rector
rector: vendor ## Automatic code fixes with Rector
	composer rector

.PHONY: phpcs
phpcs:
	vendor/bin/phpcs --standard=phpcs.xml --extensions=php --tab-width=4 -spv src tests

.PHONY: php-cs-fixer
php-cs-fixer: vendor ## Fix code style
	composer php-cs-fixer

.PHONY: phpstan-sonar
phpstan-sonar:
	./vendor/bin/phpstan analyse --error-format=json --no-progress -n > reports/phpstan/report.json src tests

.PHONY: psalm-sonar
psalm-sonar:
	bin/psalm --report=reports/psalm/sonarqube.json --config=psalm.xml

.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

.PHONY: phpda
phpda:
	docker run --rm -v $PWD:/app mamuz/phpda

.PHONY: deptrack
	./vendor/bin/deptrac --formatter=json > reports/deptrack/report.json

.PHONY: phpinsights
phpinsights:
	./vendor/bin/phpinsights analyse --composer=/home/rsalwa/projects/php/interview-client-php/composer.json

.PHONY: test_unit phpmetrics
phpmetrics:
	$(MAKE) test_unit
	${ROOT_DIR}/vendor/bin/phpmetrics --config=${ROOT_DIR}/reports/config/phpmetrics.yml ${ROOT_DIR}/src

.PHONY: phpunit
phpunit: ### run test
	${ROOT_DIR}/vendor/bin/phpunit --configuration ${ROOT_DIR}/reports/config/phpunit.xml

.PHONY: test_unit
test_unit: ### run test
	./vendor/bin/phpunit --configuration ${ROOT_DIR}/reports/config/phpunit.xml --testsuite=unit

.PHONY: test_integration
test_integration: ### run test
	./vendor/bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=integration

.PHONY: test_api
test_api: ### run test
	./vendor/bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=api

.PHONY: test_functional
test_functional: ### run test
	./vendor/bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=functional

.PHONY: test_e2e
test_e2e: ### run test
	./vendor/bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=e2e


.PHONY: proto
proto:
	@ if ! which protoc > /dev/null; then \
		echo "error: protoc not installed" >&2; \
		exit 1; \
	fi
		protoc --proto_path=proto --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=bin/grpc_php_plugin proto/*.proto;

