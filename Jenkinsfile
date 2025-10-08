pipeline {
    agent any

    stages {
        stage("code") {
            steps {
                echo "this is cloning the code"
                git url: "https://github.com/Nandkishor-Jagtap/task-management.git", branch:"main"
                echo "code cloning successfully"
            }
        }

        stage("build") {
            steps {
                echo "this is building the code"
                sh "docker build -t task-management:latest ."
            }
        }

        stage("push to dockerhub") {
            steps {
                echo "pushing image to dockerhub"
                withCredentials([usernamePassword(
                    'credentialsId':"dockerhubCred",
                    passwordVariable:"dockerHubPass",
                    usernameVariable:"dockerHubUser")]){
                sh "docker login -u $dockerHubUser -p $dockerHubPass"
                sh "docker image tag task-management:latest $dockerHubUser/task-management:latest" 
                sh "docker push $dockerHubUser/task-management:latest"
                }
            }
        }

        stage("deploy") {
            steps {
                echo "this is deploying the code"
                sh "docker-compose up -d"
            }
        }
    } 
} 

