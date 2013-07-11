Table4you
=================

Installation
---------------

install vendors
* php composer.phar install

create database
* php app/console doctrine:database:create

update database schema
* php app/console doctrine:schema:update --force

create user
* php app/console fos:user:create admin admin@test.com admin --super-admin
