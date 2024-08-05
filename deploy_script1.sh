#!/bin/bash

#echo '-------------------------------------------------' &&
#echo 'Redirect to source'
#echo '-----------------------' &&
cd /home/ubuntu/source/api &&
echo '-------------------------------------------------' &&
echo 'Pull develop' &&
echo '------------------------' &&
git pull origin develop  &&
echo '-------------------------------------------------' &&
echo 'Redirect to docker' &&
echo '------------------------' &&
cd /home/ubuntu/source/api/docker
echo '-------------------------------------------------' &&
echo 'composer install' &&
echo '------------------------' &&
docker compose exec app sh -c "cd api && composer install" &&
echo '-------------------------------------------------' &&
echo 'config:clear' &&
echo '------------------------' &&
docker compose exec app sh -c "cd api && php artisan config:clear" &&
echo '-------------------------------------------------' &&
echo 'config:cache' &&
echo '------------------------' &&
docker compose exec app sh -c "cd api && php artisan config:cache" &&
echo '-------------------------------------------------' &&
echo 'migrate' &&
echo '------------------------' &&
docker compose exec app sh -c "cd api && php artisan migrate" &&
echo '-------------------------------------------------' &&
echo 'l5-swagger:generate' &&
echo '------------------------' &&
docker compose exec app sh -c "cd api && php artisan l5-swagger:generate" &&
echo '-------------------------------------------------' &&
echo 'End -------------------- '
