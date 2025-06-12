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

RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Installer composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier tout le code Laravel
COPY . .

# Copie ton cert dans l'image
# COPY resources/certs/ca-cert.pem /etc/ssl/certs/ca-cert.pem

# Assure-toi que les permissions sont correctes
# RUN chmod 644 /etc/ssl/certs/ca-cert.pem

# Copier les assets frontend buildés par Vite
COPY --from=node-builder /app/public/build /var/www/public/build

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copier la config nginx complète (voir fichier séparé nginx.conf)
# Copier la config nginx complète
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copier le script d’entrée
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exposer le port
EXPOSE 80

# Lancer le script
CMD ["/entrypoint.sh"]

