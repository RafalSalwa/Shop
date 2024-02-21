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

.PHONY: run-always-ci
run-always-ci:
	bin/easy-ci check-commented-code src
	bin/easy-ci check-conflicts src
	bin/easy-ci find-multi-classes src


run-always:
	bin/swiss-knife check-commented-code src
	bin/swiss-knife check-conflicts src
	bin/swiss-knife find-multi-classes src
	bin/swiss-knife namespace-to-psr-4 src --namespace-root "App\\"
	echo 'RUN'

.PHONY: lint
lint: run-always
	bin/parallel-lint src

.PHONY: ecs
ecs:
	bin/ecs check src --config reports/config/ecs.php

.PHONY: rector
rector: vendor ## Automatic code fixes with Rector
	composer rector

.PHONY: phpcs
phpcs:
	bin/phpcs --standard=reports/config/phpcs.xml src tests

.PHONY: php-cs-fixer
php-cs-fixer:
	bin/php-cs-fixer --config=.php-cs-fixer.dist.php --format=checkstyle fix --dry-run > php-cs-fixer.checkstyle.xml

.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

.PHONY: deptrack
deptrack:
	./bin/deptrac --formatter=json > reports/deptrack/report.json

.PHONY: phpstan-checkstyle
phpstan-checkstyle:
	-bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=checkstyle src > reports/results/phpstan.checkstyle.xml

.PHONY: phpinsights
phpinsights:
	bin/phpinsights analyse --composer=composer.json --config-path=phpinsights.php

.PHONY: jenkins_static_analysis
jenkins_static_analysis:
	$(MAKE) test_unit
	-bin/phpcs --standard=phpcs.xml --extensions=php --tab-width=4 --report=checkstyle --report-file=reports/results/phpcs.checkstyle.xml -sp src tests || true
	-bin/phpstan analyse --error-format=checkstyle --no-progress -n src > reports/results/phpstan.checkstyle.xml || true
	-bin/psalm --report=reports/results/psalm.sonarqube.json --config=reports/config/psalm.xml || true
	-bin/php-cs-fixer --config=.php-cs-fixer.dist.php --format=checkstyle fix --dry-run > reports/results/php-cs-fixer.checkstyle.xml || true
	-bin/phpmd src/ html phpmd.xml > reports/results/phpmd.html || true
	-bin/phpmd src/ xml phpmd.xml > reports/results/phpmd.xml || true
	-bin/phpinsights analyse src --composer=composer.json --format=checkstyle > reports/results/phpinsights.xml

.PHONY: sonar_static_analysis
sonar_static_analysis:
	$(MAKE) test_unit
	-bin/psalm --report=reports/results/psalm.sonarqube.json --config=psalm.xml
	-bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=json src > reports/results/phpstan.report.json || true
	sonar-scanner -Dsonar.host.url=${SONAR_HOST} -Dsonar.token=${SONAR_TOKEN}

.PHONY: test_unit phpmetrics
phpmetrics:
	$(MAKE) test_unit
	${ROOT_DIR}/bin/phpmetrics --config=${ROOT_DIR}/reports/config/phpmetrics.yml ${ROOT_DIR}/src

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

