#!/usr/bin/env groovy

stages {

    stage('Get code from SCM') {
        checkout(
                [$class: 'GitSCM', branches: [[name: '*/#your-dev-branch#']],
                 doGenerateSubmoduleConfigurations: false,
                 extensions: [],
                 submoduleCfg: [],
                 userRemoteConfigs: [[url: '#your-git-link#']]]
        )
    }

    stage('Composer Install') {
        sh 'composer install'
    }

    stage("PHPLint") {
        sh 'find app -name "*.php" -print0 | xargs -0 -n1 php -l'
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


    stage("Publish Coverage") {
        publishHTML (target: [
                allowMissing: false,
                alwaysLinkToLastBuild: false,
                keepAll: true,
                reportDir: 'reports/phpunit',
                reportFiles: 'index.html',
                reportName: "Coverage Report"

        ])
    }



        stage('Static Analysis') {
            parallel {
              stage('CodeSniffer') {
                  steps {
                      sh 'vendor/bin/phpcs --standard=phpcs.xml .'
                  }
              }
              stage('PHP Compatibility Checks') {
                  steps {
                      sh 'vendor/bin/phpcs --standard=phpcs-compatibility.xml .'
                  }
              }
              stage('PHPStan') {
                  steps {
                      sh 'vendor/bin/phpstan analyse --error-format=checkstyle --no-progress -n . > build/logs/phpstan.checkstyle.xml'
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
                    referenceJobName: "repo-name/master",
                    tools: [
                        phpCodeSniffer(id: 'phpcs', name: 'CodeSniffer', pattern: 'build/logs/phpcs.checkstyle.xml', reportEncoding: 'UTF-8'),
                        phpStan(id: 'phpstan', name: 'PHPStan', pattern: 'build/logs/phpstan.checkstyle.xml', reportEncoding: 'UTF-8'),
                        phpCodeSniffer(id: 'phpcompat', name: 'PHP Compatibility', pattern: 'build/logs/phpcs-compat.checkstyle.xml', reportEncoding: 'UTF-8')
                    ]
                ])
            }
        }

    stage("Publish Clover") {
        step([$class: 'CloverPublisher', cloverReportDir: 'build/logs', cloverReportFileName: 'clover.xml'])
    }

    stage('Checkstyle Report') {
        sh 'vendor/bin/phpcs --report=checkstyle --report-file=build/logs/checkstyle.xml --standard=phpcs.xml --extensions=php,inc --ignore=autoload.php --ignore=vendor/ app || exit 0'
        checkstyle pattern: 'build/logs/checkstyle.xml'
    }

    stage('Mess Detection Report') {
        sh 'vendor/bin/phpmd app xml phpmd.xml --reportfile build/logs/pmd.xml --exclude vendor/ --exclude autoload.php || exit 0'
        pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
    }

    stage('CPD Report') {
        sh 'phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor app || exit 0' /* should be vendor/bin/phpcpd but... conflicts... */
        dry canRunOnFailed: true, pattern: 'build/logs/pmd-cpd.xml'
    }


    stage('Software metrics') {
        sh 'vendor/bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependencies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg --ignore=vendor app'
    }

    stage('Generate documentation') {
        sh 'vendor/bin/phpdox -f phpdox.xml'
    }
    stage('Publish Documentation') {
        publishHTML (target: [
                allowMissing: false,
                alwaysLinkToLastBuild: false,
                keepAll: true,
                reportDir: 'build/phpdox/html',
                reportFiles: 'index.html',
                reportName: "PHPDox Documentation"

        ])
    }

}