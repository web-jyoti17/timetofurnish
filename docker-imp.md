docker compose up -d --build
docker exec -it timetofurnish_app sh
docker compose exec app composer install --no-interaction
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force


docker exec timetofurnish_app php artisan migrate --path=database/migrations/2026_05_31_000002_create_offer_product_table.php


docker exec -it timetofurnish_app bash
root@0f45458fd582:/var/www/html# chmod -R 775 storage bootstrap/cachephp artisan optimize:clear
