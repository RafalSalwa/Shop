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
                publishHTML([
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: false,
                    reportDir: 'var/reports/phpunit',
                    reportFiles: 'index.html',
                    reportName: 'Coverage Report (HTML)',
                    reportTitles: 'PHPUnit'
                ])
                recordCoverage(tools: [[parser: 'COBERTURA', pattern: 'var/reports/phpunit/phpunit.cobertura.xml']])
            }
        }
        stage('Static Analysis')
        {
            parallel{
                stage('CodeSniffer') {
                    steps {
                        sh 'make phpcs env=jenkins'
                    }
                }
                stage('PHPStan') {
                    steps {
                        sh 'make phpstan env=jenkins'
                    }
                }

                stage('Mess Detection Report') {
                    steps{
                        sh 'make phpmd env=jenkins'
                        publishHTML([
                            allowMissing: false,
                            alwaysLinkToLastBuild: false,
                            keepAll: false,
                            reportDir: 'var/reports',
                            reportFiles: 'phpmd.html',
                            reportName: 'Coverage Report (HTML)',
                            reportTitles: 'PHPUnit'
                        ])
                    }
                }

                stage('Software metrics') {
                    steps{
                        sh 'bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependencies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg --ignore=vendor app'
                    }
                }
            }
        }
    }
}
