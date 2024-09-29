FROM php:8.2-fpm
COPY php.ini /usr/local/etc/php/

RUN apt-get update \
  && apt-get install -y zlib1g-dev mariadb-client vim libzip-dev libmagickwand-dev --no-install-recommends \
  && docker-php-ext-install zip pdo_mysql \
  && pecl install imagick \
  && docker-php-ext-enable imagick

# コンテナのキャッシュ削減のためのクリーンアップ（オプション）
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# 作業ディレクトリの設定
WORKDIR /var/www/laravel_kirthread

# laravelのインストーラをグローバルにインストールする
RUN composer global require "laravel/installer"

# node.js / npmのインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs

# [wip]npm installの実行が現状できないので、appコンテナ内で実行する必要がある
# またnpm run devはnginxコンテナが別に存在するので現状動作しない。npm run buildでコンパイルすること。
# RUN npm install