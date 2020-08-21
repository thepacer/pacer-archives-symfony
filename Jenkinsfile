pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        slackSend (message: "${currentBuild.fullDisplayName} Build started (<${env.BUILD_URL}|Open>)", color: '#37b787')
        sh 'bundle install'
        sh 'composer install'
        sh 'npm ci'
        sh 'npm run build'
      }
      post {
        success {
          slackSend (message: "${currentBuild.fullDisplayName} Success after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#37b787')
        }
        failure {
          slackSend (message: "${currentBuild.fullDisplayName} Failed after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#ff0000')
        }
      }
    }
    stage('Test') {
      steps {
        slackSend (message: "${currentBuild.fullDisplayName} Deploy started (<${env.BUILD_URL}|Open>)", color: '#37b787')
        sh 'bin/phpunit'
      }
      post {
        success {
          slackSend (message: "${currentBuild.fullDisplayName} Success after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#37b787')
        }
        failure {
          slackSend (message: "${currentBuild.fullDisplayName} Failed after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#ff0000')
        }
      }
    }
    stage('Deploy to Production') {
      when{
        branch 'master'
      }
      steps {
        slackSend (message: "${currentBuild.fullDisplayName} Deploy started (<${env.BUILD_URL}|Open>)", color: '#37b787')
        sh 'bundle exec cap production deploy'
      }
      post {
        success {
          slackSend (message: "${currentBuild.fullDisplayName} Success after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#37b787')
        }
        failure {
          slackSend (message: "${currentBuild.fullDisplayName} Failed after ${currentBuild.durationString.minus(' and counting')} (<${env.BUILD_URL}|Open>)", color: '#ff0000')
        }
      }
    }
  }
}
