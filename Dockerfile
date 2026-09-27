FROM php:8.2-cli

# Install dependencies system yang dibutuhkan
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (untuk build Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /app

# Copy seluruh file project
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies & Build assets
RUN npm ci && npm run build

# Create sqlite database file as fallback
RUN mkdir -p database && touch database/database.sqlite

# Optimize Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expose port yang digunakan Render
EXPOSE 10000

# Script yang dijalankan saat server start
CMD php artisan migrate --force && \
    php artisan storage:link 2>/dev/null; \
    php artisan serve --host=0.0.0.0 --port=10000
