# --- 1. Базовий образ ---
FROM php:8.3-fpm

# --- 2. Встановлення базових утиліт ---
RUN apt-get update && apt-get install -y --no-install-recommends \
    git zip unzip curl \
    && rm -rf /var/lib/apt/lists/*

# --- 3. PHP-розширення ---
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql intl mbstring opcache

# --- 4. Xdebug (опціонально, якщо потрібно для дебагу) ---
 RUN pecl install xdebug && docker-php-ext-enable xdebug
 COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# --- 5. Composer ---
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# --- 6. Робоча директорія ---
WORKDIR /var/www

# --- 7. Копіюємо лише composer файли (для кешу) ---
COPY composer.json composer.lock ./

# --- 8. Встановлюємо залежності ---
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# --- 9. Копіюємо код проекту ---
COPY . .

# --- 10. Права для кешу та логів Symfony ---
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

# --- 11. Виконати міграції при старті ---
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# --- 12. Команда за замовчуванням ---
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
