#!/bin/bash

cd /home/ubuntu/source/api
git pull origin develop

cd /home/ubuntu/source/api/docker

docker compose exec app sh -c "cd api && composer install"
docker compose exec app sh -c "cd api && php artisan config:clear"
docker compose exec app sh -c "cd api && php artisan config:cache"
docker compose exec app sh -c "cd api && php artisan migrate"

docker compose exec app sh -c "cd api && php artisan l5-swagger:generate"
