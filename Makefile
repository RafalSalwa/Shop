all: test testrace

compose-up:
	docker compose up --build -d -f docker/docker-compose.yml && docker compose logs -f

compose-down:
	docker-compose down --remove-orphans -f docker/docker-compose.yml

test: ### run test
	go test -v -cover -race ./internal/... ./pkg/... ./cmd/...
.PHONY: test

proto:
	@ if ! which protoc > /dev/null; then \
		echo "error: protoc not installed" >&2; \
		exit 1; \
	fi
		protoc --proto_path=proto --php_out=src/grpc --grpc_out=src/grpc/php --plugin=protoc-gen-grpc=bin/grpc_php_plugin proto/*.proto;