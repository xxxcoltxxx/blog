# Простой блог - пример моего кода
Проект разработан исключительно для того, чтобы показать моё понимание ООП

Блог разработан с нуля. Из стороннего кода используется только [robmorgan/phinx](https://github.com/robmorgan/phinx) для миграций баз данных и [twbs/bootstrap](https://github.com/twbs/bootstrap) для верстки css. Каждый файл был создан вручную, ничего ни откуда не копировал.

Возможно, некоторые идеи заимствованы из существующих фреймворков, но это только потому, что мне так пользоваться удобней.

Возможности:
* Авторизация;
* Список постов;
* Просмотр поста;
* Комментирование поста.

Комментировать посты могут все, создавать - только авторизованные, редактировать и удалять - только создатели поста.

# Демо
Демо можно посмотреть по адресу http://blog.colt-web.ru

# Установка блога
1. Создайте чистую базу данных
```sql
CREATE DATABASE blog CHARACTER SET utf8 COLLATE utf8_general_ci
```
2. Создайте хост
```
<VirtualHost *:80>
    DocumentRoot /var/www/blog.local/htdocs
    ServerName blog.local
    <Directory /var/www/blog.local/htdocs>
        Require all granted
    </Directory>

    ErrorLog /var/www/blog.local/logs/error_log
    CustomLog /var/www/blog.local/logs/access_log common
</VirtualHost>
```
3. Скачайте проект и установите зависимости
```sh
$ git clone https://github.com/xxxcoltxxx/blog.git .
$ php -r "readfile('https://getcomposer.org/installer');" | php
$ php composer.phar install
$ vendor/bin/phinx init
$ vi phinx.yml
```
В файле phinx.yml настройте доступ к базе данных. Нужны все права для работы с таблицами (ALTER, DROP, CREATE, INSERT, SELECT и т.д.)
```
paths:
    migrations: %%PHINX_CONFIG_DIR%%/migrations

environments:
    default_migration_table: phinxlog
    default_database: development
    ...
    development:
        adapter: mysql
        host: localhost
        name: blog
        user: root
        pass: ''
        port: 3306
        charset: utf8
    ...
```

4. Запустите миграцию базы данных
```sh
$ vendor/bin/phinx migrate
```
Настройте кодоступ к базе данных для блога
```sh
$ cp include/config/databases.ini.example include/config/databases.ini
$ vi include/config/databases.ini
```
```
[main]
host = localhost
username = root
password =
database = blog
encoding = UTF8
```
5. Убедитесь, что у вас включен `mod_rewrite` и в качестве файла `htaccess` установлен `.htaccess`
6. Создайте папку для логов хоста, если её нет
```sh
$ mkdir logs
```
7. Перезапустите apache
```sh
$ /etc/init.d/apache2 configtest
$ /etc/init.d/apache2 reload
```
8. Если вы работаете на локальном компьютере, добавьте запись в `/etc/hosts`
```sh
$ sudo echo "127.0.0.1" >> /etc/hosts
```
9. Добавьте пользователя для блога
```sql
INSERT INTO `users` (`email`, `password`, `name`)
VALUES
('user@mail.ru', MD5('12345678'), 'Пользователь')
```
10. Откройте ваш хост (http://blog.local) в браузере и пользуйтесь!
Логин: *user@mail.ru*
Пароль: *12345678*
