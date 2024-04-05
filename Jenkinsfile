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
                stage('Psalm') {
                    steps {
                        sh 'make psalm env=jenkins'
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

                stage('Deptrac') {
                    steps {
                        sh 'make deptrac'
                    }
                }
            }
        }
        stage('Fixers')
        {
            parallel{
                stage('PHP-CS-Fixer') {
                    steps {
                        sh 'vendor/bin/php-cs-fixer --config=config/analysis/php-cs-fixer.php check --diff --verbose'
                    }
                }
                stage('Rector') {
                    steps {
                        sh 'vendor/bin/rector process --dry-run'
                    }
                }
                stage('TwigCS') {
                    steps {
                        sh 'vendor/bin/twigcs templates'
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
                            reportName: 'Mess Detection (HTML)',
                            reportTitles: 'PHPMD'
                        ])
                    }
                }

                stage('Deptrac') {
                    steps {
                        sh 'make deptrac'
                    }
                }
            }
        }
    }
}
