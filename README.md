Запуск проекта:
1. `git clone`
2. `cp .env.example .env`
3. настроить `.env`
4. `docker-compose build`
5. `docker-compose up -d`
6. `docker-compose exec php-cli composer install`
7. `docker-compose exec php-cli php artisan storage:link`
8. `docker compose exec php-cli php artisan migrate:fresh`
9. `docker compose exec php-cli php artisan key:generate`
