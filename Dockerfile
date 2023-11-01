
# Use an official PHP runtime as a parent image
FROM php:8.0-apache


ADD ./src /var/www/html
# Set the working directory
# WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY src/ /var/www/html

RUN apt-get update && apt upgrade -y

RUN apt-get install -y libmariadb-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# CMD ["php", "-S", "localhost:8000"]








