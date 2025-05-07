
FROM php:8.1-apache AS builder

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libmariadb-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

RUN a2enmod rewrite

COPY . /var/www/html/


FROM php:8.1-apache AS production

RUN apt-get update && apt-get install -y libmariadb-dev \
    && docker-php-ext-install mysqli

RUN a2enmod rewrite

COPY --from=builder /var/www/html /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
