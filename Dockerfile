FROM php:7.2-apache

RUN sed -ri -e 's!AllowOverride .*!AllowOverride All!g' /etc/apache2/*/*.conf /etc/apache2/apache2.conf
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
