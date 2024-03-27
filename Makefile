export ROOT_DIR=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

all:

up:
	docker compose up -d && docker compose logs -f nginx php
up_local:
	symfony server:stop
	-killall webpack
	docker compose up -d
	symfony server:start -d --no-tls
	symfony run -d --watch=config,src,templates,vendor symfony
	symfony run -d yarn encore dev-server --port 9001
	symfony server:log

prod:
	composer dump-autoload --no-dev --classmap-authoritative
down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

phive_installation:
	phive install phploc
	phive install churn # churn run src tests

lint:
	vendor/bin/parallel-lint src --no-progress

twig_lint:
	vendor/bin/twigcs src --no-interaction

.PHONY: cloc
cloc:
	cloc . --vcs git --exclude-dir=vendor,public,node_modules
.PHONY: ecs
ecs:
	vendor/bin/ecs check src --config config/analysis/ecs.php

phpcs:
	vendor/bin/phpcs --standard=config/analysis/phpcs.xml -s src tests

psalm:
	vendor/bin/psalm --config=config/analysis/psalm.xml --no-cache --no-file-cache --no-reflection-cache

.PHONY: php-cs-fixer
php-cs-fixer:
	vendor/bin/php-cs-fixer --config=.php-cs-fixer.dist.php --format=checkstyle fix --dry-run > php-cs-fixer.checkstyle.xml

.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

deptrac:
	-./vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml --formatter=graphviz-image --output=var/reports/deptrack.png
	-./vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml --formatter=junit --output=var/reports/deptrack.junit.xml
	vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml
.PHONY: phpstan
phpstan: lint
	-vendor/bin/phpstan analyse --configuration=config/analysis/phpstan.neon src

.PHONY: phpinsights
phpinsights:
	vendor/bin/phpinsights analyse --composer=composer.json --config-path=phpinsights.php

.PHONY: phparkitect
phparkitect:
	vendor/bin/phparkitect check --config=config/analysis/phparkitect.php

statistics:
	vendor/bin/phpmetrics --config=config/analysis/phpmetrics.yml --report-html=var/results/phpmetrics src/
	vendor/bin/pdepend --summary-xml=var/reports/pdepend.summary.xml --jdepend-chart=var/reports/pdepend.chart.svg --overview-pyramid=var/reports/pdepend.pyramid.svg src/
	vendor/bin/phpinsights analyse src --config-path=config/analysis/phpinsights.php --no-interaction --format=checkstyle > reports/results/phpinsights.xml

static_analysis: lint test_unit
	vendor/bin/phparkitect check --config=config/analysis/phparkitect.php
#	-vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml --formatter=junit --output=reports/results/deptrack.junit.xml
	-vendor/bin/phpcs --standard=config/analysis/phpcs.xml -s src tests
	-vendor/bin/psalm --config=config/analysis/psalm.xml --no-cache --no-file-cache --no-reflection-cache || true
	-vendor/bin/phpstan analyse --configuration=config/analysis/phpstan.neon --no-progress -n src || true
	-vendor/bin/php-cs-fixer --config=config/analysis/php-cs-fixer.php check --diff --verbose || true
#	-vendor/bin/phpmd src/ html config/analysis/phpmd.xml > reports/results/phpmd.html || true
#	-vendor/bin/phpmd src/ xml config/analysis/phpmd.xml > reports/results/phpmd.xml || true
#	-vendor/bin/phpmetrics --config=config/analysis/phpmetrics.yml src/
#	-vendor/bin/twigcs templates --reporter checkstyle > reports/results/twigcs.xml
	-vendor/bin/rector process --dry-run
# pdepend!

.PHONY: lint test_unit
jenkins_static_analysis:
	$(MAKE) test_unit
	-vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml --formatter=junit --output=./var/reports/deptrack.junit.xml
	-vendor/bin/phpcs --standard=config/analysis/phpcs.xml --report=checkstyle --report-file=./var/reports/phpcs.checkstyle.xml src tests || true
	-vendor/bin/phpstan analyse --configuration=config/analysis/phpstan.neon --error-format=checkstyle --no-progress -n src > ./var/reports/phpstan.checkstyle.xml || true
	-vendor/bin/psalm --config=config/analysis/psalm.xml --report=./var/reports/psalm.sonarqube.json || true
	-vendor/bin/php-cs-fixer --config=config/analysis/php-cs-fixer.php --format=checkstyle fix --dry-run > ./var/reports/php-cs-fixer.checkstyle.xml || true
	-vendor/bin/phpmd src/ html config/analysis/phpmd.xml > ./var/reports/phpmd.html || true
	-vendor/bin/phpmd src/ xml config/analysis/phpmd.xml > ./var/reports/phpmd.xml || true
	-vendor/bin/phpinsights analyse src --config-path=config/analysis/phpinsights.php --composer=composer.json --no-interaction --format=checkstyle > ./var/reports/phpinsights.xml
	-vendor/bin/phpmetrics --config=config/analysis/phpmetrics.yml src/
	-vendor/bin/twigcs templates --reporter checkstyle > ./var/reports/twigcs.xml

.PHONY: lint test_unit
github_actions_static_analysis:
	vendor/bin/phparkitect check --config=config/analysis/phparkitect.php
	vendor/bin/deptrac --config-file=config/analysis/deptrac.yaml --formatter=github-actions
	vendor/bin/phpcs --standard=config/analysis/phpcs.xml --report=checkstyle | cs2pr
	-vendor/bin/phpstan analyse --configuration=config/analysis/phpstan.neon --error-format=github --no-progress -n src || true
	-vendor/bin/psalm --config=config/analysis/psalm.xml --output-format=github || true
	-vendor/bin/php-cs-fixer --config=config/analysis/php-cs-fixer.php --format=checkstyle fix --dry-run | cs2pr || true
	-vendor/bin/phpmd src/ github config/analysis/phpmd.xml || true
	-vendor/bin/phpinsights analyse src --config-path=config/analysis/phpinsights.php --no-interaction --composer=composer.json --format=github-action

.PHONY: lint test_unit
sonar_static_analysis:
	-vendor/bin/psalm --report=./var/reports/psalm.sonarqube.json --config=config/analysis/psalm.xml
	-vendor/bin/phpstan analyse --configuration=config/analysis/phpstan.neon --error-format=json src > ./var/reports/phpstan.sonarqube.report.json || true
	sonar-scanner -Dsonar.host.url=${SONAR_HOST} -Dsonar.token=${SONAR_TOKEN}

.PHONY: test_unit
phpmetrics:
	${ROOT_DIR}/vendor/bin/phpmetrics --config=${ROOT_DIR}/config/analysis/phpmetrics.yml ${ROOT_DIR}/src
	xdg-open ${ROOT_DIR}/reports/results/phpmetrics/html/index.html >/dev/null

.PHONY: test_unit
test_unit: ### run test
	./vendor/bin/phpunit --configuration ./config/analysis/phpunit.xml --testsuite=unit --no-output

.PHONY: test_integration
test_integration: ### run test
	./vendor/bin/phpunit --configuration ./config/analysis/phpunit.xml --testsuite=integration

.PHONY: test_api
test_api: ### run test
	./vendor/bin/phpunit --configuration ./config/analysis/phpunit.xml --testsuite=api

.PHONY: test_functional
test_functional: ### run test
	./vendor/bin/phpunit --configuration ./config/analysis/phpunit.xml --testsuite=functional

.PHONY: test_e2e
test_e2e: ### run test
	./vendor/bin/phpunit --configuration ./config/analysis/phpunit.xml --testsuite=e2e

.PHONY: phpdoc
phpdoc:
	docker run --rm -v "$(pwd):/data" "phpdoc/phpdoc:3"

.PHONY: proto
proto:
	@ if ! which protoc > /dev/null; then \
		echo "error: protoc not installed" >&2; \
		exit 1; \
	fi
		protoc --proto_path=proto --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=bin/grpc_php_plugin proto/*.proto;
	-rm -rf src/Protobuf/
	mv src/App/Protobuf src/
	rm -rf src/App/
.PHONY: git_remote_cleaner
git_remote_cleaner:
	git remote prune origin
	git fetch -p && for branch in $(git for-each-ref --format '%(refname) %(upstream:track)' refs/heads | awk '$2 == "[gone]" {sub("refs/heads/", "", $1); print $1}'); do git branch -D $branch; done
