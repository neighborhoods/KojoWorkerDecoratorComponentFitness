FROM neighborhoods/php-fpm:php-8.0-datadog

# Install unzip, procps, cmake, bc, and vim.
RUN apt-get update -y \
&& apt-get install -y \
apt-utils unzip procps gdbserver cmake bc vim lsof iputils-ping wget libpq-dev curl libcurl3-dev
RUN apt-get install -y postgresql-client || true

# Set working directory to project root.
ARG PROJECT_NAME=kojo_worker_decorator_component_fitness
ENV PROJECT_DIRECTORY=/var/www/html/${PROJECT_NAME}
WORKDIR $PROJECT_DIRECTORY

## Copy to Dockerfile build context.
#COPY . $PROJECT_DIRECTORY

# Copy PHP initialization files.
COPY configuration/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY configuration/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY configuration/phar.ini /usr/local/etc/php/conf.d/phar.ini

# Install PCNTL.
RUN docker-php-ext-install pcntl

# Install Postgres.
RUN docker-php-ext-install pdo_pgsql

# Install Opcache.
RUN docker-php-ext-install opcache

# Install Curl.
RUN docker-php-ext-install curl

# Install Xdebug.
ENV PHP_IDE_CONFIG="serverName=kojo_worker_decorator_component_fitness"
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Install Redis
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Install Composer.
COPY install-composer.sh $PROJECT_DIRECTORY
RUN ./install-composer.sh

RUN mkdir -p data/cache/Opcache/HTTPBuildableDirectoryMap
RUN mkdir -p data/cache/Opcache/DNS
RUN chmod -R a+rw data/cache/

# Create Specific Prefab HTTP Containers.
#RUN $PROJECT_DIRECTORY/bin/prime-container.sh

ENV PATH="$PROJECT_DIRECTORY/bin/:${PATH}"

CMD ["php-fpm"]

EXPOSE 9000

