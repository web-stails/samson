# samson
Тестовое задание выполнено на Laravel 9
================

###### Реализовать страницу получения уникального кода скидки. Авторизованный пользователь переходит на новую страницу, где видит кнопку "Получить скидку". При нажатии на кнопку, ему отображается случайное значение скидки от 1 до 50% и уникальный код для получения данной скидки. Если пользователь в течении 1 часа повторно перешел на данную страницу и нажал кнопку "Получить скидку", то ему отображается старая скидка. Так же на странице отображается поле ввода, для проверки кода и кнопка "Проверить скидку". Авторизованный пользователь вводит в поле вода ранее полученный код и нажимает кнопку "Проверить скидку". Если скидка была получена более 3 часов назад или скидка была получена для другого пользователя, то ему отображается уведомление "Скидка недоступна", иначе отображается значение скидки. Необходимо предусмотреть хранение значения кода и соответствующей скидки для каждого пользователя. Подготовить скрипты для формирования структуры хранения данных и заполнения тестовыми данными.


Для развертывания проекта выполнить следующие действия:
----------------------

###### предполагается что web сервер уже развернут

Установка
---------

> composer install

или

> php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" 

>php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

>php composer-setup.php

>php -r "unlink('composer-setup.php');"

>php composer.phar install


Далее, файл .env.example переименовать в .env

и прописать в нем данные подключения к бд

>DB_CONNECTION=mysql # если база mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE={база}

>DB_USERNAME={логин}

>DB_PASSWORD={пароль}

>DB_DATABASE_TEST={тестовая база}

>DB_USERNAME_TEST={логин}

>DB_PASSWORD_TEST={пароль}

домен проекта

*APP_URL=http://samson.my*

Далее, запускаем миграции

> php artisan migrate

Установливаем пакеты npm

>npm install

>npm run dev

Права на директории
-------------------

1 надо сделать web сервер владельцем файлов

>sudo chown -R www-data:www-data /path/to/your/laravel/root/directory

2 устанавливаем 644 на файлы

>sudo find /path/to/your/laravel/root/directory -type f -exec chmod 644 {} \;

3 устанавливаем 755 на папки

>sudo find /path/to/your/laravel/root/directory -type d -exec chmod 755 {} \;

4 Даем права web серверу записывать storage и cache

>sudo chgrp -R www-data storage bootstrap/cache

>sudo chmod -R ug+rwx storage bootstrap/cache

Сгенерировать ключ шифрования для проекта
------------------------
>php artisan key:generate


Проект запущен
-------------------

переходит по адресу домена, регистрируем пользователя и авторизуемся

Авто наполнение данными
-------------------

По логике тестового задания и его реализации наполнять базовыми данными нет необходимости. Однако авто наполнение добавлено, для запуска нужно выполнить команду

>php artisan db:seed

Переходим к тестированию
-------------------

Для запуска тестов нужно выполнить команду

>php artisan test