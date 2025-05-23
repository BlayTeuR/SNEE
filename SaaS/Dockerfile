# Étape 1 : Build des assets avec Node et Vite
FROM node:18-alpine as node-builder

WORKDIR /app

COPY package.json vite.config.* tailwind.config.* postcss.config.* ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm install && npm run build

# Étape 2 : Laravel avec PHP et Composer
FROM php:8.2-fpm-alpine

# Dépendances système
RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    zlib-dev \
    libzip-dev \
    postgresql-dev \
    nodejs \
    npm

# Extensions PHP nécessaires à Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www

# Copier tout le code
COPY . .

# Copier les assets générés
COPY --from=node-builder /app/public/build /var/www/public/build

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Donner les droits
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Port exposé (si tu utilises nginx ailleurs, pas besoin ici)
EXPOSE 9000

CMD ["php-fpm"]
