ARG PHP_IMAGE
FROM ${PHP_IMAGE}
ARG EXPECTED_PHP
ARG EXPECTED_SOURCE_SHA256
RUN test "$(php -r 'echo PHP_VERSION;')" = "$EXPECTED_PHP"
RUN printf '%s  /usr/src/php.tar.xz\n' "$EXPECTED_SOURCE_SHA256" | sha256sum -c -
COPY docker/lab.ini /usr/local/etc/php/conf.d/zz-lab.ini
COPY docker/lab.conf /etc/apache2/conf-enabled/zz-oak-lab.conf
COPY public/ /var/www/html/
COPY app/ /var/www/app/
RUN find /var/www/html /var/www/app -name '*.php' -exec php -l {} + \
    && apache2ctl configtest
LABEL org.opencontainers.image.title="Oak Street Cycles PHP comparison sites"
LABEL org.opencontainers.image.description="Identical booking site on PHP 8.4.26 and 8.4.27; localhost only."
