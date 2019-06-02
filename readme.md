# Тестовое задание "простое медиахранилище"

## Развёртывание

git clone https://github.com/dim1100101/test-task-simbirsoft.git

composer install

## Настройки

**в файле .env**

Прописываем нужный URL приложениа:

APP_URL=http://localhost

Конфигурируем БД:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=homestead

DB_USERNAME=

DB_PASSWORD=


## Создаём структуру БД

php artisan migrate

## Создаём аккаунт адмиинистратора

php artisan admin:create

## По желанию заполняем БД тестовыми данными

php artisan db:seed