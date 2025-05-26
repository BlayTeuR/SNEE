# Étape 1 : Build des assets frontend avec Node et Vite
FROM node:18-alpine as node-builder

WORKDIR /app

COPY package.json package-lock.json vite.config.* tailwind.config.* postcss.config.* ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm install
RUN npm run build

# Étape 2 : Backend Laravel avec PHP-FPM
FROM php:8.2-fpm-alpine

# Installer dépendances système + extensions PHP
RUN apk add --no-cache \
    bash curl libpng libpng-dev libjpeg-turbo-dev freetype-dev zip unzip git oniguruma-dev libxml2-dev icu-dev zlib-dev libzip-dev postgresql-dev nginx

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Installer composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier tout le code Laravel
COPY . .

# Copier les assets frontend buildés par Vite
COPY --from=node-builder /app/public/build /var/www/public/build

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copier la config nginx complète (voir fichier séparé nginx.conf)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Exposer le port 80 (HTTP)
EXPOSE 80

# Lancer PHP-FPM + nginx en premier plan
CMD php-fpm -D && nginx -g "daemon off;"
