#!/bin/sh
set -e

# Якщо vendor відсутній — виконай composer install
if [ ! -d "vendor" ]; then
  echo "📦 Встановлюю залежності..."
  composer install --no-interaction --prefer-dist --optimize-autoloader

  echo "📁 Копіюю vendor на хост..."
  cp -r vendor /tmp/vendor-copy
  chown -R $(stat -c "%u:%g" .) /tmp/vendor-copy
  cp -r /tmp/vendor-copy/* ./vendor/
fi

echo "⏳ Очікую на MySQL..."
until php -r "try { new PDO('mysql:host=mysql;dbname=app', 'user', 'password'); exit(0); } catch (Exception \$e) { exit(1); }"; do
  sleep 1
done
echo "✅ MySQL доступний!"

echo "🚀 Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || true

echo "🔥 Clearing and warming up cache..."
php bin/console cache:clear --no-warmup || true

exec "$@"
