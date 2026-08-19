#!/bin/bash
# What the web container does before it serves anything: the two PHP extensions
# the app needs, mod_rewrite, ownership of the two writable trees, the schema,
# and the demo data. It runs on every `up`, so every step is either idempotent
# or guarded.
#
# pdo_mysql compiles from sources bundled in the image, so it needs no network.
# Nothing else can be added here: this is Debian stretch, its apt repositories
# are archived, and the sandbox will not proxy them. That is why the demo runs
# on the OpenSSL encrypter rather than mcrypt.
set -euo pipefail
cd /var/www/html

php -m | grep -q pdo_mysql || docker-php-ext-install pdo_mysql mysqli > /dev/null 2>&1
a2enmod rewrite > /dev/null 2>&1
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan migrate --force

# DemoSeeder truncates before it inserts, so seeding twice is harmless. The
# guard is here so that stopping and starting the containers keeps whatever was
# clicked through in between; `down` drops the database with the container, and
# the next `up` seeds again.
tickets=$(php -r '
  try {
      $pdo = new PDO(
          sprintf("mysql:host=%s;dbname=%s", getenv("DB_HOST"), getenv("DB_DATABASE")),
          getenv("DB_USERNAME"),
          getenv("DB_PASSWORD")
      );
      echo (int) $pdo->query("select count(*) from tickets")->fetchColumn();
  } catch (Exception $e) {
      echo 0;
  }
')

if [ "$tickets" -eq 0 ]; then
  php artisan db:seed --class=DemoSeeder --force
fi

echo "Convergence is on http://localhost:8080, as demo / demo"
exec apache2-foreground
