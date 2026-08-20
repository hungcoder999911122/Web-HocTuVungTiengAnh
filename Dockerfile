# file mô tả cách Docker tạo ra môi trường PHP cho project.

# bắt đầu từ môi trường PHP 8.2 có Apache.
FROM php:8.2-apache 

RUN docker-php-ext-install pdo pdo_mysql

# --------------------------