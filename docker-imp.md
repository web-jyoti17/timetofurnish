docker compose up -d --build
docker exec -it timetofurnish_app sh
docker compose exec app composer install --no-interaction
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
