# Etapa 1: dependências PHP + Composer
FROM php:8.2-cli AS php-base
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Etapa 2: Node para build do Vue
FROM node:18 AS node-build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 3: Final - rodar Laravel
FROM php-base
WORKDIR /app

# Copiar arquivos do projeto
COPY . .
COPY --from=node-build /app/public/js /app/public/js
COPY --from=node-build /app/public/css /app/public/css

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Start command
CMD php artisan serve --host 0.0.0.0 --port $PORT