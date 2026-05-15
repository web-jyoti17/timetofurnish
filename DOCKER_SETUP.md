# Docker Setup (Laravel)

## 1) Prepare environment file

```bash
cp .env.docker .env
```

If `.env` already exists and you want to keep it, create a Docker-only copy and use that as reference.

## 2) Build and start containers

```bash
docker compose up -d --build
```

## 3) Initialize Laravel inside the app container

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## 4) Database setup

Option A: run migrations

```bash
docker compose exec app php artisan migrate
```

Option B: import existing SQL dump

```bash
docker compose exec -T mysql mysql -uroot -proot_password timetofurnish < sqlupdates/v27.sql
```

## 5) Access URLs

- App: http://localhost:8000
- phpMyAdmin: http://localhost:8081
  - Server: `mysql`
  - Username: `root`
  - Password: `root_password`

## Useful commands

```bash
docker compose logs -f app
docker compose down
docker compose down -v
```
