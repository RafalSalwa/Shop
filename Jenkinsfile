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
                sh 'vendor/bin/phpunit'
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
                recordCoverage(tools: [[parser: 'COBERTURA', pattern: 'build/logs/cobertura.xml']])
            }
        }
        stage('Static Analysis') {
            parallel {
                stage('CodeSniffer') {
                    steps {
                        sh 'vendor/bin/phpcs --standard=phpcs.xml --report=checkstyle --report-file=reports/phpcs/phpcs.checkstyle.xml --extensions=php --tab-width=4 -spv src tests'
                    }
                }
                stage('PHPStan') {
                    steps {
                        sh 'vendor/bin/phpstan analyse --error-format=checkstyle --no-progress -n . > reports/phpstan/phpstan.checkstyle.xml'
                    }
                }
//                 stage("Publish Clover") {
//                     step([$class: 'CloverPublisher', cloverReportDir: 'build/logs', cloverReportFileName: 'clover.xml'])
//                 }

                stage('Mess Detection Report') {
                    sh 'vendor/bin/phpmd src checkstyle phpmd.xml --reportfile reports/phpmd/pmd.xml --exclude vendor/ --exclude autoload.php'
                    pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
                }

                stage('Software metrics') {
                    sh 'vendor/bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependencies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg --ignore=vendor app'
                }

                stage('Generate documentation') {
                    sh 'vendor/bin/phpdox -f phpdox.xml'
                }
            }

    }
}
