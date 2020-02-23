1. Склонировать репозиторий
2. Установить компоненты: composer install
3. Создать базу данных : php bin/console doctrine:database:create
4. Выполнить миграцию php bin/console doctrine:migrations:migrate
5. Запустить приложение: php bin/console server:run
Необходимые компоненты:
PHP 7.4.3
Установленный composer
Локальный MySQL сервер
