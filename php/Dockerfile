# Stage 1: Build stage
FROM php:8.1-apache AS builder

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

RUN a2enmod rewrite

COPY . /var/www/html/

FROM php:8.1-apache AS production

COPY --from=builder /var/www/html /var/www/html

RUN a2enmod rewrite

EXPOSE 80

WORKDIR /var/www/html

CMD ["apache2-foreground"]
