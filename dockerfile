# Etapa 1: PHP + Composer + Apache
FROM php:8.2-apache AS backend

# Instala módulos necessários
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia todo o projeto
COPY . .

# Instala dependências do backend
RUN composer install --no-dev --optimize-autoloader

# Etapa 2: Frontend (Node.js)
FROM node:18 AS frontend

WORKDIR /app

# Copia e instala dependências JS
COPY ./package.json ./package-lock.json ./
RUN npm install

# Copia arquivos e roda o build do Vue/Vite
COPY . .
RUN npm run build

# Etapa final: combina os dois
FROM backend AS final

# Copia os assets buildados para a pasta pública do Laravel
COPY --from=frontend /app/dist /var/www/html/public

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]