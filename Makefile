export ROOT_DIR=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

all:

up:
	symfony server:stop
	docker compose up -d
	symfony server:start -d --no-tls
	symfony run -d --watch=config,src,templates,vendor symfony
	symfony run -d yarn encore dev-server --port 9001
	symfony server:log

down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

.PHONY: run-always
run-always:
	bin/swiss-knife check-commented-code src
	bin/swiss-knife check-conflicts src
	bin/swiss-knife find-multi-classes src
.PHONY: lint
lint:
	bin/parallel-lint src

.PHONY: ecs
ecs:
	bin/ecs check src --config reports/config/ecs.php
.PHONY: rector
rector: vendor ## Automatic code fixes with Rector
	composer rector

.PHONY: phpcs
phpcs:
	bin/phpcs --standard=phpcs.xml src tests

.PHONY: php-cs-fixer
php-cs-fixer:
	bin/php-cs-fixer --config=.php-cs-fixer.dist.php --format=checkstyle fix --dry-run > php-cs-fixer.checkstyle.xml


.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

.PHONY: phpda
phpda:
	docker run --rm -v $PWD:/app mamuz/phpda

.PHONY: deptrack
deptrack:
	./bin/deptrac --formatter=json > reports/deptrack/report.json

.PHONY: phpinsights
phpinsights:
	bin/phpinsights analyse --composer=/home/rsalwa/projects/php/interview-client-php/composer.json

.PHONY: phpstan-sonar
phpstan-sonar:
	-bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=json src > reports/results/phpstan.report.json

.PHONY: psalm-sonar
psalm-sonar:
	-bin/psalm --report=reports/results/psalm.sonarqube.json --config=psalm.xml

.PHONY: sonar_static_analysis
sonar_static_analysis:
	$(MAKE) test_unit
	$(MAKE) psalm-sonar
	$(MAKE) phpstan-sonar || true
	sonar-scanner -Dsonar.host.url=${SONAR_HOST} -Dsonar.token=${SONAR_TOKEN}

.PHONY: test_unit phpmetrics
phpmetrics:
	$(MAKE) test_unit
	${ROOT_DIR}/bin/phpmetrics --config=${ROOT_DIR}/reports/config/phpmetrics.yml ${ROOT_DIR}/src

.PHONY: phpunit
phpunit: ### run test
	${ROOT_DIR}/bin/phpunit --configuration ${ROOT_DIR}/reports/config/phpunit.xml

.PHONY: test_unit
test_unit: ### run test
	./bin/phpunit --configuration ${ROOT_DIR}/reports/config/phpunit.xml --testsuite=unit

.PHONY: test_integration
test_integration: ### run test
	./bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=integration

.PHONY: test_api
test_api: ### run test
	./bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=api

.PHONY: test_functional
test_functional: ### run test
	./bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=functional

.PHONY: test_e2e
test_e2e: ### run test
	./bin/phpunit --configuration ./reports/config/phpunit.xml --testsuite=e2e


.PHONY: proto
proto:
	@ if ! which protoc > /dev/null; then \
		echo "error: protoc not installed" >&2; \
		exit 1; \
	fi
		protoc --proto_path=proto --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=bin/grpc_php_plugin proto/*.proto;

