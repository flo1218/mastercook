pipeline {
    agent any

    stages {
        stage('Install Dependencies') {
            steps {
                // Installer les dépendances
                sh 'composer install'
            }
        }
        stage('Run Tests') {
            steps {
                // Exécuter les tests
                sh './vendor/bin/phpunit'
            }
        }
        stage('Build') {
            steps {
                // Construire l'application (si nécessaire)
                sh 'npm install && npm run build'
            }
        }
    }

    post {
        always {
            // Actions à effectuer après le pipeline (par ex., nettoyage)
            cleanWs()
        }
        success {
            echo 'Pipeline terminé avec succès.'
        }
        failure {
            echo 'Le pipeline a échoué.'
        }
    }
}
