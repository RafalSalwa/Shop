# interview-client-php

[![Psalm lvl 2](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml)
[![PHPStan lvl 8](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml)
[![PHPMD - Mess Detector](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml)
[![Architecture & Dependencies](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml)

[![PHPCS - Code Sniffer](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml)
[![Build Status](https://jenkins.salwa.com.pl/job/Shop/badge/icon?subject=Jenkins)](https://jenkins.salwa.com.pl/job/Shop/)

simple REST Server with REST and gRPC clients to communicate
with go  [grpc&REST interview server](https://github.com/RafalSalwa/interview-app-srv)
# Build
At first create docker network if You did not do so for server

next steps are to build docker
```bash
make compose-up
```
add hosts entry for nginx vhost
```bash
echo "0.0.0.0  interview.local" >> /etc/hosts
```
postman collection is in docs folder

## Screenshots:
![REST](docs/rest.png)
![grpc](docs/grpc.png)

