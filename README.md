# interview-client-php

[![Psalm lvl 2](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/psalm.yml)
[![PHPStan lvl 8](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpstan.yml)
[![PHPMD - Mess Detector](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpmd.yml)
[![Architecture & Dependencies](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/architecture_dependencies.yml)
[![codecov](https://codecov.io/gh/RafalSalwa/interview-client-php/graph/badge.svg?token=DOR8PFOKFQ)](https://codecov.io/gh/RafalSalwa/interview-client-php)
[![PHPCS - Code Sniffer](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml/badge.svg)](https://github.com/RafalSalwa/interview-client-php/actions/workflows/phpcs.yml)
[![PHPCS-Fixer](https://github.com/RafalSalwa/Shop/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/RafalSalwa/Shop/actions/workflows/php-cs-fixer.yml)

[![Rector](https://github.com/RafalSalwa/Shop/actions/workflows/rector.yaml/badge.svg)](https://github.com/RafalSalwa/Shop/actions/workflows/rector.yaml)
[![Build Status](https://jenkins.salwa.com.pl/job/Shop/badge/icon?subject=Jenkins)](https://jenkins.salwa.com.pl/job/Shop/)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2FRafalSalwa%2Finterview-client-php.svg?type=shield&issueType=license)](https://app.fossa.com/projects/git%2Bgithub.com%2FRafalSalwa%2Finterview-client-php?ref=badge_shield&issueType=license)

Codacy:
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/7621ab51388d4f4aa5b0528030eb5f57)](https://app.codacy.com/gh/RafalSalwa/interview-client-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/7621ab51388d4f4aa5b0528030eb5f57)](https://app.codacy.com/gh/RafalSalwa/interview-client-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)


SonarQube: [![Lines of Code](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=ncloc&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)

[![Duplicated Lines (%)](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=duplicated_lines_density&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)
[![Maintainability Rating](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=sqale_rating&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)
[![Quality Gate Status](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=alert_status&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)
[![Reliability Rating](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=reliability_rating&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)
[![Security Rating](http://jenkins.salwa.com.pl:9000/api/project_badges/measure?project=Shopping-App&metric=security_rating&token=sqb_c1e655532b49e9a7ea5bae138d85db73e5a986b0)](http://jenkins.salwa.com.pl:9000/dashboard?id=Shopping-App)



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

