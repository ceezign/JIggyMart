# JiggyMart — Docker image for Render deployment.
#
# Render has no native PHP runtime, so PHP/Laravel apps are deployed as
# Docker containers. This follows Render's own official Laravel template
# (richarvey/nginx-php-fpm, which bundles Nginx + PHP-FPM + the common
# extensions Laravel needs, including pdo_pgsql for our Neon database).
FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Allow composer to run as root inside the container
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]
