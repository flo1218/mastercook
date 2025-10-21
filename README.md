# MasterCook

MasterCook is a sample application to learn Symfony framework

## Setup

1. git clone <https://github.com/flo1218/mastercook>  
2. cd mastercook  
3. docker-compose up -d  
4. docker exec -it symrecipe_app composer install  
5. docker exec -it symrecipe_app npm install  
6. docker exec -it symrecipe_app php bin/console doctrine:migrations:migrate  
7. docker exec -it symrecipe_app php bin/console doctrine:fixtures:load  
