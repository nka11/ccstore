FROM php:7.2-apache
RUN docker-php-ext-install mysqli \
     && docker-php-ext-enable mysqli \
     && docker-php-ext-install pdo_mysql \
     && docker-php-ext-enable pdo_mysql
RUN sed -ri -e 's!AllowOverride .*!AllowOverride All!g' /etc/apache2/*/*.conf /etc/apache2/apache2.conf
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
