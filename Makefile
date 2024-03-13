export ROOT_DIR=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

all:

up:
	symfony server:stop
	-killall webpack
	docker compose up -d
	symfony server:start -d --no-tls
	symfony run -d --watch=config,src,templates,vendor symfony
	symfony run -d yarn encore dev-server --port 9001
	symfony server:log

down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

.PHONY: run-always-ci
run-always-ci:
	vendor/bin/easy-ci check-commented-code src
	vendor/bin/easy-ci check-conflicts src
	vendor/bin/easy-ci find-multi-classes src

run-always:
	vendor/bin/swiss-knife check-commented-code src
	vendor/bin/swiss-knife check-conflicts src
	vendor/bin/swiss-knife find-multi-classes src
	# vendor/bin/swiss-knife namespace-to-psr-4 src --namespace-root "App\\"

.PHONY: lint
lint: run-always
	vendor/bin/parallel-lint src --blame --no-progress
.PHONY: cloc
cloc:
	cloc . --vcs git --exclude-dir=vendor,public,node_modules
.PHONY: ecs
ecs:
	vendor/bin/ecs check src --config reports/config/ecs.php

.PHONY: rector
rector: vendor ## Automatic code fixes with Rector
	composer rector

.PHONY: phpcs
phpcs:
	vendor/bin/phpcs --standard=reports/config/phpcs.xml src tests

.PHONY: php-cs-fixer
php-cs-fixer:
	vendor/bin/php-cs-fixer --config=.php-cs-fixer.dist.php --format=checkstyle fix --dry-run > php-cs-fixer.checkstyle.xml

.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

.PHONY: deptrac
deptrac:
	-./vendor/bin/deptrac --config-file=reports/config/deptrac.yaml --formatter=graphviz-image --output=reports/results/deptrack.png
	-./vendor/bin/deptrac --config-file=reports/config/deptrac.yaml --formatter=junit --output=reports/results/deptrack.junit.xml

.PHONY: phpstan
phpstan: lint
	-vendor/bin/phpstan analyse --configuration=reports/config/phpstan.neon src

.PHONY: phpinsights
phpinsights:
	vendor/bin/phpinsights analyse --composer=composer.json --config-path=phpinsights.php

.PHONY: jenkins_static_analysis
jenkins_static_analysis:
	$(MAKE) test_unit
	-vendor/bin/deptrac --config-file=reports/config/deptrac.yaml --formatter=junit --output=reports/results/deptrack.junit.xml
	-vendor/bin/phpcs --standard=reports/config/phpcs.xml --report=checkstyle --report-file=reports/results/phpcs.checkstyle.xml src tests || true
	-vendor/bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=checkstyle --no-progress -n src > reports/results/phpstan.checkstyle.xml || true
	-vendor/bin/psalm --config=reports/config/psalm.xml --report=reports/results/psalm.sonarqube.json --debug-by-line || true
	-vendor/bin/php-cs-fixer --config=reports/config/php-cs-fixer.php --format=checkstyle fix --dry-run > reports/results/php-cs-fixer.checkstyle.xml || true
	-vendor/bin/phpmd src/ html reports/config/phpmd.xml > reports/results/phpmd.html || true
	-vendor/bin/phpmd src/ xml reports/config/phpmd.xml > reports/results/phpmd.xml || true
	-vendor/bin/phpinsights analyse src --config-path=reports/config/phpinsights.php --composer=composer.json --no-interaction --format=checkstyle > reports/results/phpinsights.xml
	-vendor/bin/phpmetrics --config=reports/config/phpmetrics.yml src/
	-vendor/bin/twigcs templates --reporter checkstyle > reports/results/twigcs.xml

.PHONY: github_actions_static_analysis
github_actions_static_analysis:
	#$(MAKE) test_unit
	vendor/bin/deptrac --config-file=reports/config/deptrac.yaml --formatter=github-actions
#	-vendor/bin/phpcs --standard=reports/config/phpcs.xml --report=checkstyle --report-file=reports/results/phpcs.checkstyle.xml src tests || true
#	-vendor/bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=checkstyle --no-progress -n src > reports/results/phpstan.checkstyle.xml || true
#	-vendor/bin/psalm --config=reports/config/psalm.xml --report=reports/results/psalm.sonarqube.json --debug-by-line || true
#	-vendor/bin/php-cs-fixer --config=reports/config/php-cs-fixer.php --format=checkstyle fix --dry-run > reports/results/php-cs-fixer.checkstyle.xml || true
	-vendor/bin/phpmd src/ html reports/config/phpmd.xml > reports/results/phpmd.html || true
#	-vendor/bin/phpmd src/ xml reports/config/phpmd.xml > reports/results/phpmd.xml || true
	-vendor/bin/phpinsights analyse src --config-path=reports/config/phpinsights.php --composer=composer.json --format=github-action
#	-vendor/bin/phpmetrics --config=reports/config/phpmetrics.yml src/


.PHONY: sonar_static_analysis
sonar_static_analysis:
	$(MAKE) test_unit
	-vendor/bin/psalm --report=reports/results/psalm.sonarqube.json --config=psalm.xml
	-vendor/bin/phpstan analyse --configuration=reports/config/phpstan.neon --error-format=json src > reports/results/phpstan.report.json || true
	sonar-scanner -Dsonar.host.url=${SONAR_HOST} -Dsonar.token=${SONAR_TOKEN}

.PHONY: phparkitect
phparkitect:
	vendor/bin/phparkitect check --config=reports/config/phparkitect.php

.PHONY: test_unit phpmetrics
phpmetrics:
	$(MAKE) test_unit
	${ROOT_DIR}/vendor/bin/phpmetrics --config=${ROOT_DIR}/reports/config/phpmetrics.yml ${ROOT_DIR}/src
	xdg-open ${ROOT_DIR}/reports/results/phpmetrics/html/index.html >/dev/null
.PHONY: test_unit
test_unit: ### run test
	./vendor/bin/phpunit --configuration ${ROOT_DIR}/reports/config/phpunit.xml --testsuite=unit --no-output

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
		protoc --proto_path=proto --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=vendor/bin/grpc_php_plugin proto/*.proto;

.PHONY: git_remote_cleaner
git_remote_cleaner:
	git remote prune origin
	git fetch -p && for branch in $(git for-each-ref --format '%(refname) %(upstream:track)' refs/heads | awk '$2 == "[gone]" {sub("refs/heads/", "", $1); print $1}'); do git branch -D $branch; done
