# Shop app with cart

static analysis:

[![Psalm lvl 2](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml)
[![Type Coverage](https://shepherd.dev/github/rafalsalwa/shop/coverage.svg)](https://shepherd.dev/github/rafalsalwa/shop)
[![PHPStan lvl 8](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml)
[![PHPMD - Mess Detector](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml)

Code analysis:

[![PHPCS - Code Sniffer](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml)
[![PHPCS-Fixer](https://github.com/RafalSalwa/Shop/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/RafalSalwa/Shop/actions/workflows/php-cs-fixer.yml)
[![Rector](https://github.com/RafalSalwa/Shop/actions/workflows/rector.yaml/badge.svg)](https://github.com/RafalSalwa/Shop/actions/workflows/rector.yaml)

Architecture:

[![Architecture & Dependencies](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml)

External services

[![Build Status](https://jenkins.salwa.com.pl/job/Shop/badge/icon?subject=Jenkins)](https://jenkins.salwa.com.pl/job/Shop/)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2FRafalSalwa%2Finterview-client-php.svg?type=shield&issueType=license)](https://app.fossa.com/projects/git%2Bgithub.com%2FRafalSalwa%2Finterview-client-php?ref=badge_shield&issueType=license)
[![codecov](https://codecov.io/gh/RafalSalwa/interview-client-php/graph/badge.svg?token=DOR8PFOKFQ)](https://codecov.io/gh/RafalSalwa/interview-client-php)

Codacy:
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/7621ab51388d4f4aa5b0528030eb5f57)](https://app.codacy.com/gh/RafalSalwa/interview-client-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/7621ab51388d4f4aa5b0528030eb5f57)](https://app.codacy.com/gh/RafalSalwa/interview-client-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)


SonarQube: [![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=RafalSalwa_Shop&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=RafalSalwa_Shop)

---

Simple and feature rich Shopping cart based on PHP 8.2, Symfony 6, PostgresSQL, OAuth2, gRPC and custom [grpc&REST Auth api](https://github.com/RafalSalwa/auth-api)

Featured with Shopping cart, profile management, Order processing flow with abandoned carts and pendings. Features based on subscription tiers.

## ⚙️ Installation
```bash
make up
```
or via symfony internal server installed on host:
```bash
make local
```

then You can visit [http://127.0.0.1:8001/](http://127.0.0.1:8001/)

Also, there is a openApi documentation at [http://127.0.0.1:8001/doc](http://127.0.0.1:8001/doc) or postman collection is in [docs](docs/RSShop.postman_collection.json) folder

## 🎯 Features
- Products listings based on subscription tier
- Flow control with Symfony Workflows and Security Voters
- cart management via view or REST Api
- REST communication with Auth-api via JWT Tokens for authentication flow in User Providers and Authenticators
- OAuth2 server integration for authorization flow
- Doctrine entities with Inheritance pattern and abstraction for cart and order items
- GitHub actions, Jenkins, Gitlab integrations
- gRPC clients for external (golang) [Auth-api](https://github.com/RafalSalwa/auth-api)
- Clean layered architecture checked by [PHPArkitect and deptrac](.github/workflows/architecture_dependencies.yml)
- Static analysis with [PHPStan lvl 7](.github/workflows/phpstan.yml), [Psalm lvl 2](.github/workflows/psalm.yml), [PHPMD](.github/workflows/phpmd.yml), [PHPCS](.github/workflows/phpcs.yml), [PHP-CS-Fixer](.github/workflows/php-cs-fixer.yml), [Rector](.github/workflows/rector.yaml)
- External tools for quality analysis PHPMetrics, PHPInsights, Snyk, SemGrep, SonarQube, Codacy & codecov


## Screenshots:
![deptrac](docs/deptrack.png)
![REST](docs/rest.png)
![grpc](docs/grpc.png)

