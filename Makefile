all: test testrace

compose-up:
	docker compose up --build -d -f docker/docker-compose.yml && docker compose logs -f

compose-down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

.PHONY: rector
rector: vendor ## Automatic code fixes with Rector
	composer rector

.PHONY: php-cs-fixer
php-cs-fixer: vendor ## Fix code style
	composer php-cs-fixer

.PHONY: stan
stan: ## Runs static analysis with phpstan
	composer stan

.PHONY: bench
bench: ## Runs benchmarks with phpbench
	composer bench

test: ### run test
	go test -v -cover -race ./internal/... ./pkg/... ./cmd/...
.PHONY: test

.PHONY: proto
proto:
	@ if ! which protoc > /dev/null; then \
		echo "error: protoc not installed" >&2; \
		exit 1; \
	fi
		protoc --proto_path=proto --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=bin/grpc_php_plugin proto/*.proto;