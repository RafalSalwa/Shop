#!/usr/bin/env groovy

pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                checkout scm;
                sh 'composer install';

            }
        }
        stage('Unit Tests') {
            steps {
                sh 'make test_unit'
                xunit([
                    thresholds: [
                        failed ( failureThreshold: "0" ),
                        skipped ( unstableThreshold: "0" )
                    ],
                    tools: [
                        PHPUnit(pattern: 'reports/config/phpunit.xml', stopProcessingIfError: true, failIfNotNew: true)
                    ]
                ])
                publishHTML([
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: false,
                    reportDir: 'reports/phpunit',
                    reportFiles: 'index.html',
                    reportName: 'Coverage Report (HTML)',
                    reportTitles: ''
                ])
                discoverGitReferenceBuild()
                recordCoverage(tools: [[parser: 'COBERTURA', pattern: 'reports/phpunit/cobertura.xml']])
            }
        }
        stage('Static Analysis')
        {
            parallel{
                stage('CodeSniffer') {
                    steps {
                        sh 'bin/phpcs --standard=phpcs.xml --report=checkstyle --report-file=reports/phpcs/phpcs.checkstyle.xml -spv src tests'
                    }
                }
                stage('PHPStan') {
                    steps {
                        sh 'bin/phpstan analyse --error-format=checkstyle --no-progress -n . > reports/phpstan/phpstan.checkstyle.xml'
                    }
                }

                stage('Mess Detection Report') {
                    steps{
                        sh 'bin/phpmd src checkstyle phpmd.xml --reportfile reports/phpmd/pmd.xml --exclude vendor/ --exclude autoload.php'
                        pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
                    }
                }

                stage('Software metrics') {
                    steps{
                        sh 'bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependencies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg --ignore=vendor app'
                    }
                }

                stage('Generate documentation') {
                    steps{
                        sh 'bin/phpdox -f phpdox.xml'
                    }
                }
            }
        }
        post {
            always {
                recordIssues([
                    sourceCodeEncoding: 'UTF-8',
                    enabledForFailure: true,
                    aggregatingResults: true,
                    blameDisabled: true,
                    tools: [
                        phpCodeSniffer(id: 'phpcs', name: 'CodeSniffer', pattern: 'reports/phpcs/phpcs.checkstyle.xml', reportEncoding: 'UTF-8'),
                        phpStan(id: 'phpstan', name: 'PHPStan', pattern: 'reports/phpstan/phpstan.checkstyle.xml', reportEncoding: 'UTF-8'),
                    ]
                ])
            }
        }
    }
}
