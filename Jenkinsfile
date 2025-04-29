pipeline {
    agent any

    stages {
        stage('Install PHP') { 
            steps {
                // Installer PHP dans le conteneur Docker
                sh 'apt-get update && apt-get install -y php-cli'
            }
        }
        stage('Install Dependencies') {
            steps {
                // Vérifier si Composer est installé et l'utiliser
                sh 'php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"'
                sh 'php composer-setup.php --install-dir=/usr/local/bin --filename=composer'
                sh 'php -r "unlink(\'composer-setup.php\');"'
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
