FROM debian:buster

RUN apt-get update && apt-get install  -y  \
    git \
    vim \
    curl \
    ca-certificates \
    apache2 \
    wget \
    software-properties-common \
    && apt-get clean

# PHP 7.4 ##################################################################################################
RUN apt install -y apt-transport-https lsb-release && \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list 

RUN apt update && \
    apt install -y php7.4 php7.4-cli php7.4-fpm php-pear php7.4-dev php7.4-common && \
    apt install -y php7.4-bcmath php7.4-curl php7.4-gd php7.4-intl php7.4-json php7.4-mbstring php7.4-mysql php7.4-opcache php7.4-sqlite3 php7.4-xml php7.4-zip php7.4-pdo && \
    apt install -y libapache2-mod-php7.4

# Pecl extensions
RUN pecl install \
    redis-5.2.0 \ 
    xdebug-2.8.1

# Enabling pecl extensions PHP INI
RUN echo "extension=redis" >> /etc/php/7.4/apache2/php.ini && echo "extension=redis" >> /etc/php/7.4/cli/php.ini

# Enabling error logging
RUN sed -i 's|;error_log = php_errors.log|error_log = /var/www/php_errors.log|g' /etc/php/7.4/cli/php.ini && sed -i 's|;error_log = php_errors.log|error_log = /var/www/php_errors.log|g' /etc/php/7.4/apache2/php.ini
RUN sed -i 's|display_errors = Off|display_errors = On|g' /etc/php/7.4/cli/php.ini && sed -i 's|display_errors = Off|display_errors = On|g' /etc/php/7.4/apache2/php.ini
RUN sed -i 's|display_startup_errors = Off|display_startup_errors = On|g' /etc/php/7.4/cli/php.ini && sed -i 's|display_startup_errors = Off|display_startup_errors = On|g' /etc/php/7.4/apache2/php.ini

# End PHP  ################################################################################################

# Apache config
RUN a2enmod rewrite 
RUN chown -R www-data:www-data /var/www
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# End Apache config #######################################################################################

EXPOSE 80 443

HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD curl -f http://localhost || exit 1

COPY "./scripts/web-start.sh" "/scripts/web-start.sh"

ENTRYPOINT ["/bin/sh", "/scripts/web-start.sh"]